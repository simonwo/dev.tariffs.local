-- This file outputs all of the tariff measures associated with
-- each commodity code contained in a TARIC3-style database.
-- Each commodity code will appear once for each measure that
-- is registered against it. Commodity codes without their own
-- measures will display the measures of their ancestor.

-- The file uses temporary tables and is designed to be run in
-- a transaction. A number of PostgreSQL-specific features are used.

-- (c) Simon Worthington 2020
-- Released under the Open Government Licence v3:
-- https://www.nationalarchives.gov.uk/doc/open-government-licence/version/3/
-- Based on a PHP version by Matt Lavis.

-- We create a mapping of CCs to the next CC with the same indent
-- that we will use later to inherit down down to CCs that don't
-- have their own measures. We do this using a window function by
-- ordering by CC and then selecting the next row with the same indent.
-- We do this as a temporary table rather than a CTE so that we can
-- put some indexes on it.
CREATE TEMPORARY TABLE ccs (
  cc char(10),
  sid int4,
  indent integer,
  next char(10),
  next_sid int4,
  producline_suffix char(2)
) ON COMMIT DROP;

CREATE INDEX ON ccs (producline_suffix, cc);

CREATE INDEX ON ccs (cc);

CREATE INDEX ON ccs (next);

CREATE INDEX ON ccs (sid);

CREATE INDEX ON ccs (next_sid);

INSERT INTO
  ccs
SELECT
  parents.goods_nomenclature_item_id as cc,
  parents.goods_nomenclature_sid AS sid,
  parent_indents.number_indents as indent,
  LEAD(parents.goods_nomenclature_item_id) OVER (
    PARTITION BY parent_indents.number_indents
    ORDER BY
      parents.goods_nomenclature_item_id
  ) AS next,
  LEAD(parents.goods_nomenclature_sid) OVER (
    PARTITION BY parent_indents.number_indents
    ORDER BY
      parents.goods_nomenclature_item_id
  ) AS next_sid,
  parents.producline_suffix AS producline_suffix
FROM
  goods_nomenclatures AS parents
  LEFT OUTER JOIN goods_nomenclature_indents AS parent_indents ON parents.goods_nomenclature_sid = parent_indents.goods_nomenclature_sid;

-- Now we create the mapping of CCs to all descendent CCs.
-- We do this using the table above, selecting all of the CCs
-- that exist between the two limits and at a greater indent.
-- Again we do this as a temporary table so we can take advantage
-- of indexes.
CREATE TEMPORARY TABLE cc_children (parent_sid int4, child_sid int4) ON COMMIT DROP;

CREATE INDEX ON cc_children (parent_sid);

CREATE INDEX ON cc_children (child_sid);

INSERT INTO
  cc_children
SELECT
  cc1.sid as parent_sid,
  cc2.sid as child_sid
FROM
  ccs as cc1,
  ccs as cc2
WHERE
  cc2.cc >= cc1.cc
  AND cc2.cc < cc1.next
  AND cc2.producline_suffix = '80';

CREATE TEMPORARY TABLE goods_measures (
  sid int4 NOT NULL,
  measure_sid int4 NOT NULL,
  ancestor_measure bool
) ON COMMIT DROP;

CREATE INDEX ON goods_measures (sid);

-- Measure end dates are complex. The `validity_end_date` on the
-- measures table is not an accurate reflection of when a measure
-- actually end, because it also depends on the regulation that
-- defines the measure. So this view contains a `real_end_date`
-- that actually defines when the measure stops applying.
WITH real_measures AS (
  SELECT
    m.*,
    LEAST(
      m.validity_end_date,
      r.validity_end_date,
      r.effective_end_date
    ) AS real_end_date
  FROM
    measures m,
    base_regulations r
  WHERE
    m.measure_generating_regulation_id = r.base_regulation_id
  UNION
  SELECT
    m.*,
    LEAST(
      m.validity_end_date,
      r.validity_end_date,
      r.effective_end_date
    ) AS real_end_date
  FROM
    measures m,
    modification_regulations r
  WHERE
    m.measure_generating_regulation_id = r.modification_regulation_id
),
-- We need to explicity bring this out as a separate table.
-- We want codes with no matching measures to appear blank,
-- which they won't do if we link to measures directly,
-- and then remove rows with a WHERE clause. Instead we need
-- to filter the measures first and then do a outer join.
measures_of_type AS (
  SELECT
    *
  FROM
    real_measures AS measures
  WHERE
    measures.measure_type_id IN ('142', '145') -- Tariff Preferences
  --measures.measure_type_id IN ('103', '105', '106') -- Third country duties
    AND measures.validity_start_date <= NOW()
    AND (
      measures.real_end_date > NOW()
      OR measures.real_end_date IS NULL
    )
),
-- Now we map the measures to the CCs. We do this in two ways:
-- 1. by relating the SID on the measure to the SID on the CC directly
-- 2. by "inheriting down" measures on a parent CC to any CC
--    that doesn't have its own measures
normal_measures AS (
  SELECT
    ccs.sid,
    measures_of_type.measure_sid
  FROM
    ccs,
    measures_of_type
  WHERE
    ccs.sid = measures_of_type.goods_nomenclature_sid
),
ancestor_measures AS (
  SELECT
    cc_children.child_sid AS sid,
    measures_of_type.measure_sid
  FROM
    cc_children,
    measures_of_type
  WHERE
    cc_children.parent_sid = measures_of_type.goods_nomenclature_sid
)
INSERT INTO
  goods_measures
SELECT
  *,
  FALSE AS ancestor_measure
FROM
  normal_measures
UNION
SELECT
  *,
  TRUE AS ancestor_measure
FROM
  ancestor_measures
WHERE
  NOT EXISTS (
    SELECT
      1
    FROM
      normal_measures
    WHERE
      normal_measures.sid = ancestor_measures.sid
  );

-- This is beginning of the actual query, where we use a ton of CTEs.
-- The first two map geographical areas and CCs to their most up-to-date
-- English language descriptions.
WITH measures_mapping AS (
  SELECT
    *
  FROM
    goods_measures WHERE ancestor_measure <> TRUE
),
geo_descriptions AS (
  SELECT
    *
  FROM
    (
      SELECT
        g.geographical_area_sid,
        g.geographical_area_id,
        gd.description,
        ROW_NUMBER() OVER (
          PARTITION BY g.geographical_area_id
          ORDER BY
            gdp.validity_start_date DESC
        ) AS row
      FROM
        geographical_areas AS g
        LEFT OUTER JOIN geographical_area_description_periods AS gdp ON g.geographical_area_sid = gdp.geographical_area_sid
        LEFT OUTER JOIN geographical_area_descriptions AS gd ON gdp.geographical_area_description_period_sid = gd.geographical_area_description_period_sid
    ) AS latest_desc
  WHERE
    latest_desc.row = 1
),
goods_descriptions AS (
  SELECT
    *
  FROM
    (
      SELECT
        g.goods_nomenclature_sid,
        gd.description,
        ROW_NUMBER() OVER (
          PARTITION BY g.goods_nomenclature_sid
          ORDER BY
            gdp.validity_start_date DESC
        ) AS row
      FROM
        goods_nomenclatures AS g
        LEFT OUTER JOIN goods_nomenclature_description_periods AS gdp ON g.goods_nomenclature_sid = gdp.goods_nomenclature_sid
        LEFT OUTER JOIN goods_nomenclature_descriptions AS gd ON gdp.goods_nomenclature_description_period_sid = gd.goods_nomenclature_description_period_sid
    ) AS latest_desc
  WHERE
    latest_desc.row = 1
),
goods_indents AS (
  SELECT
    *
  FROM
    (
      SELECT
        g.goods_nomenclature_sid,
        gi.number_indents,
        ROW_NUMBER() OVER (
          PARTITION BY g.goods_nomenclature_sid
          ORDER BY
            gi.validity_start_date DESC
        ) AS row
      FROM
        goods_nomenclatures AS g
        LEFT OUTER JOIN goods_nomenclature_indents AS gi ON g.goods_nomenclature_sid = gi.goods_nomenclature_sid
    ) AS latest_desc
  WHERE
    latest_desc.row = 1
),
-- We build a table of expression parts for all of the duty expressions.
-- We want the duty expression as a single string but it is composed of
-- many parts. Each part transforms directly into a single part of the
-- expression, and we then combine all of the parts together. The order
-- in which we combine them is the order of the duty_expression_id.
-- Based on https://github.com/uktrade/trade-tariff-management/tree/master/app/models/duty_expression_description.rb
expression_parts AS (
  SELECT
    measure_sid,
    duty_expression_id,
    (
      CASE
        WHEN duty_expression_id IN ('04', '12', '14', '19', '20', '21', '25', '27', '29') THEN '+'
        WHEN duty_expression_id IN ('02', '36') THEN '-'
        WHEN duty_expression_id IN ('17', '35') THEN 'MAX'
        WHEN duty_expression_id IN ('15', '36') THEN 'MIN'
        ELSE NULL
      END
    ) AS prefix,
    (
      CASE
        WHEN duty_expression_id = '12' THEN 'AC'
        WHEN duty_expression_id = '14' THEN 'AC (reduced)'
        WHEN duty_expression_id = '21' THEN 'SD'
        WHEN duty_expression_id = '25' THEN 'SD (reduced)'
        WHEN duty_expression_id = '27' THEN 'FD'
        WHEN duty_expression_id = '29' THEN 'FD (reduced)'
        WHEN duty_expression_id = '99' THEN measure_components.measurement_unit_code
        ELSE TO_CHAR(duty_amount, 'FM9999990D00') || (
          CASE
            WHEN monetary_unit_code IS NOT NULL THEN ' ' || monetary_unit_code
            ELSE '%'
          END
        )
      END
    ) AS amount,
    (
      CASE
        WHEN measure_components.measurement_unit_code = 'ASV' THEN '% vol'
        WHEN measure_components.measurement_unit_code = 'NAR' THEN 'item'
        WHEN measure_components.measurement_unit_code = 'CCT' THEN 'ct/l'
        WHEN measure_components.measurement_unit_code = 'CEN' THEN '100 p/st'
        WHEN measure_components.measurement_unit_code = 'CTM' THEN 'c/k'
        WHEN measure_components.measurement_unit_code = 'DTN' THEN '100 kg'
        WHEN measure_components.measurement_unit_code = 'GFI' THEN 'gi F/S'
        WHEN measure_components.measurement_unit_code = 'GRM' THEN 'g'
        WHEN measure_components.measurement_unit_code = 'HLT' THEN 'hl'
        WHEN measure_components.measurement_unit_code = 'HMT' THEN '100 m'
        WHEN measure_components.measurement_unit_code = 'KGM' THEN 'kg'
        WHEN measure_components.measurement_unit_code = 'KLT' THEN '1,000 l'
        WHEN measure_components.measurement_unit_code = 'KMA' THEN 'kg met.am.'
        WHEN measure_components.measurement_unit_code = 'KNI' THEN 'kg N'
        WHEN measure_components.measurement_unit_code = 'KNS' THEN 'kg H2O2'
        WHEN measure_components.measurement_unit_code = 'KPH' THEN 'kg KOH'
        WHEN measure_components.measurement_unit_code = 'KPO' THEN 'kg K2O'
        WHEN measure_components.measurement_unit_code = 'KPP' THEN 'kg P2O5'
        WHEN measure_components.measurement_unit_code = 'KSD' THEN 'kg 90 % sdt'
        WHEN measure_components.measurement_unit_code = 'KSH' THEN 'kg NaOH'
        WHEN measure_components.measurement_unit_code = 'KUR' THEN 'kg U'
        WHEN measure_components.measurement_unit_code = 'LPA' THEN 'l alc. 100%'
        WHEN measure_components.measurement_unit_code = 'LTR' THEN 'l'
        WHEN measure_components.measurement_unit_code = 'MIL' THEN '1,000 items'
        WHEN measure_components.measurement_unit_code = 'MTK' THEN 'm2'
        WHEN measure_components.measurement_unit_code = 'MTQ' THEN 'm3'
        WHEN measure_components.measurement_unit_code = 'MTR' THEN 'm'
        WHEN measure_components.measurement_unit_code = 'MWH' THEN '1,000 kWh'
        WHEN measure_components.measurement_unit_code = 'NCL' THEN 'ce/el'
        WHEN measure_components.measurement_unit_code = 'NPR' THEN 'pa'
        WHEN measure_components.measurement_unit_code = 'TJO' THEN 'TJ'
        WHEN measure_components.measurement_unit_code = 'TNE' THEN 'tonne'
        ELSE measure_components.measurement_unit_code
      END
    ) AS unit,
    (
      CASE
        WHEN measurement_unit_qualifier_code = 'A' THEN 'tot alc'
        WHEN measurement_unit_qualifier_code = 'C' THEN '1 000'
        WHEN measurement_unit_qualifier_code = 'E' THEN 'net drained wt'
        WHEN measurement_unit_qualifier_code = 'G' THEN 'gross'
        WHEN measurement_unit_qualifier_code = 'I' THEN 'of biodiesel content'
        WHEN measurement_unit_qualifier_code = 'M' THEN 'net dry'
        WHEN measurement_unit_qualifier_code = 'P' THEN 'lactic matter'
        WHEN measurement_unit_qualifier_code = 'R' THEN 'std qual'
        WHEN measurement_unit_qualifier_code = 'S' THEN ' raw sugar'
        WHEN measurement_unit_qualifier_code = 'T' THEN 'dry lactic matter'
        WHEN measurement_unit_qualifier_code = 'X' THEN ' hl'
        WHEN measurement_unit_qualifier_code = 'Z' THEN '% sacchar.'
        ELSE measurement_unit_qualifier_code
      END
    ) AS qualifier
  FROM
    measure_components
  ORDER BY
    measure_sid ASC,
    duty_expression_id ASC
),
-- Now that we have all the expression parts, we can aggregate over them
-- to form a final string duty expression for this particular measure.
-- We must make sure join the expression in duty_expression_id order,
-- otherwise the components will appear in the wrong place.
duty_expressions AS (
  SELECT
    expression_parts.measure_sid,
    string_agg(
      CONCAT(
        (
          CASE
            WHEN expression_parts.prefix IS NOT NULL THEN expression_parts.prefix || ' '
            ELSE ''
          END
        ),
        expression_parts.amount,
        (
          CASE
            WHEN expression_parts.unit IS NOT NULL THEN ' / ' || expression_parts.unit
            ELSE ''
          END
        ),
        (
          CASE
            WHEN expression_parts.qualifier IS NOT NULL THEN ' / ' || expression_parts.qualifier
            ELSE ''
          END
        )
      ),
      ' '
      ORDER BY
        expression_parts.duty_expression_id ASC
    ) AS duty_expression
  FROM
    expression_parts
  GROUP BY
    expression_parts.measure_sid
  ORDER BY
    measure_sid ASC
),
real_measures AS (
  SELECT
    m.*,
    LEAST(
      m.validity_end_date,
      r.validity_end_date,
      r.effective_end_date
    ) AS real_end_date
  FROM
    measures m,
    base_regulations r
  WHERE
    m.measure_generating_regulation_id = r.base_regulation_id
  UNION
  SELECT
    m.*,
    LEAST(
      m.validity_end_date,
      r.validity_end_date,
      r.effective_end_date
    ) AS real_end_date
  FROM
    measures m,
    modification_regulations r
  WHERE
    m.measure_generating_regulation_id = r.modification_regulation_id
)
-- The final query!
SELECT
  goods_nomenclatures.goods_nomenclature_item_id AS "Commodity code",
  goods_nomenclatures.producline_suffix::integer AS "Suffix",
  goods_indents.number_indents :: integer AS "Indent",
  goods_descriptions.description AS "Description",
  EXISTS ( SELECT 1 FROM goods_measures WHERE goods_measures.sid = goods_nomenclatures.goods_nomenclature_sid ) :: bool AS "Assigned",
  measures.measure_sid AS "Measure SID",
  measure_type_descriptions.measure_type_id::integer AS "Measure type ID",
  measure_type_descriptions.description AS "Measure type",
  duty_expressions.duty_expression AS "Duty expression",
  geo_descriptions.geographical_area_id AS "Origin ID",
  geo_descriptions.description AS "Origin",
  measures.validity_start_date::date AS "Start date",
  measures.real_end_date::date AS "End date"
FROM
  goods_nomenclatures
  LEFT OUTER JOIN measures_mapping AS goods_measures ON goods_nomenclatures.goods_nomenclature_sid = goods_measures.sid
  LEFT OUTER JOIN goods_indents ON goods_nomenclatures.goods_nomenclature_sid = goods_indents.goods_nomenclature_sid
  LEFT OUTER JOIN real_measures AS measures ON goods_measures.measure_sid = measures.measure_sid
  LEFT OUTER JOIN duty_expressions ON measures.measure_sid = duty_expressions.measure_sid
  LEFT OUTER JOIN goods_descriptions ON goods_descriptions.goods_nomenclature_sid = goods_nomenclatures.goods_nomenclature_sid
  LEFT OUTER JOIN geo_descriptions ON measures.geographical_area_sid = geo_descriptions.geographical_area_sid
  LEFT OUTER JOIN measure_type_descriptions ON measures.measure_type_id = measure_type_descriptions.measure_type_id
ORDER BY
  goods_nomenclatures.goods_nomenclature_item_id,
  goods_indents.number_indents,
  geo_descriptions.geographical_area_id