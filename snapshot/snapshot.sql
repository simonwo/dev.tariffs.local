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
    measures.measure_type_id IN (
      -- Preferential duties
      '106', -- Customs Union Duty
      '147', -- Customs Union Quota
      '142', -- Tariff preference
      '143', -- Preferential quota
      '141', -- Preferential suspension
      '145', -- Tariff preference under end use
      '146'  -- Preferential quota under end use

      -- Suspensions but not reliefs
      --'112', -- Autonomous tariff suspension
      --'115' -- Autonomous suspension under end use

      -- Third country duties
      --'103',
      --'105',
      --'106'

      -- Disputes
      --'695', -- Additional duties
      --'696'  -- Additional duties (safeguard)
    )
    -- Uncomment to remove future measures
    -- AND measures.validity_start_date <= NOW()
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
-- The first three define the common lookups for units, qualifiers and prefixes.
WITH unit_codes AS (
  SELECT * FROM (VALUES
      ('ASV', '% vol'),
      ('NAR', 'item'),
      ('CCT', 'ct/l'),
      ('CEN', '100 p/st'),
      ('CTM', 'c/k'),
      ('DTN', '100 kg'),
      ('GFI', 'gi F/S'),
      ('GRM', 'g'),
      ('HLT', 'hl'),
      ('HMT', '100 m'),
      ('KGM', 'kg'),
      ('KLT', '1,000 l'),
      ('KMA', 'kg met.am.'),
      ('KNI', 'kg N'),
      ('KNS', 'kg H2O2'),
      ('KPH', 'kg KOH'),
      ('KPO', 'kg K2O'),
      ('KPP', 'kg P2O5'),
      ('KSD', 'kg 90 % sdt'),
      ('KSH', 'kg NaOH'),
      ('KUR', 'kg U'),
      ('LPA', 'l alc. 100%'),
      ('LTR', 'l'),
      ('MIL', '1,000 items'),
      ('MTK', 'm2'),
      ('MTQ', 'm3'),
      ('MTR', 'm'),
      ('MWH', '1,000 kWh'),
      ('NCL', 'ce/el'),
      ('NPR', 'pa'),
      ('TJO', 'TJ'),
      ('TNE', 'tonne')
    ) AS unit_codes (unit_code, description)
),
unit_qualifiers AS
  (SELECT * FROM (VALUES
    ('A', 'tot alc'),
    ('C', '1 000'),
    ('E', 'net drained wt'),
    ('G', 'gross'),
    ('I', 'of biodiesel content'),
    ('M', 'net dry'),
    ('P', 'lactic matter'),
    ('R', 'std qual'),
    ('S', ' raw sugar'),
    ('T', 'dry lactic matter'),
    ('X', ' hl'),
    ('Z', '% sacchar.')
  ) AS unit_qualifiers (unit_qualifier_code, description)
),
duty_expression_prefixes AS
  (SELECT * FROM (VALUES
    ('04', '+'),
    ('12', '+'),
    ('14', '+'),
    ('19', '+'),
    ('20', '+'),
    ('21', '+'),
    ('25', '+'),
    ('27', '+'),
    ('29', '+'),
    ('02', '-'),
    ('36', '-'),
    ('17', 'MAX'),
    ('35', 'MAX'),
    ('15', 'MIN'),
    ('36', 'MIN')
  ) AS duty_expression_prefixes (duty_expression_id, description)
),
measures_mapping AS (
  SELECT
    *
  FROM
    goods_measures WHERE ancestor_measure <> TRUE
),
-- The next two map geographical areas and CCs to their most up-to-date
-- English language descriptions.
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
    measure_components.duty_expression_id,
    duty_expression_prefixes.description AS prefix,
    (
      CASE
        WHEN measure_components.duty_expression_id = '12' THEN 'AC'
        WHEN measure_components.duty_expression_id = '14' THEN 'AC (reduced)'
        WHEN measure_components.duty_expression_id = '21' THEN 'SD'
        WHEN measure_components.duty_expression_id = '25' THEN 'SD (reduced)'
        WHEN measure_components.duty_expression_id = '27' THEN 'FD'
        WHEN measure_components.duty_expression_id = '29' THEN 'FD (reduced)'
        WHEN measure_components.duty_expression_id = '99' THEN measure_components.measurement_unit_code
        ELSE TO_CHAR(duty_amount, 'FM9999990D00') || (
          CASE
            WHEN monetary_unit_code IS NOT NULL THEN ' ' || monetary_unit_code
            ELSE '%'
          END
        )
      END
    ) AS amount,
    unit_codes.description AS unit,
    unit_qualifiers.description AS qualifier
  FROM
    measure_components
    LEFT OUTER JOIN duty_expression_prefixes ON duty_expression_prefixes.duty_expression_id = measure_components.duty_expression_id
    LEFT OUTER JOIN unit_codes ON measure_components.measurement_unit_code = unit_codes.unit_code
    LEFT OUTER JOIN unit_qualifiers ON measure_components.measurement_unit_qualifier_code = unit_qualifiers.unit_qualifier_code
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
        (CASE WHEN prefix IS NOT NULL THEN prefix || ' ' ELSE '' END),
        amount,
        (CASE WHEN unit IS NOT NULL THEN ' / ' || unit ELSE '' END),
        (CASE WHEN qualifier IS NOT NULL THEN ' / ' || qualifier ELSE '' END)
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
-- We now repeat the above exercise for measure condition components.
-- These are used in forming complex expressions for the Entry Price System.
-- The first two CTEs are all of the duty expressions from measure condition components.
condition_component_parts AS (
  SELECT
    measure_condition_sid,
    measure_condition_components.duty_expression_id,
    duty_expression_prefixes.description AS prefix,
    (
      CASE
        WHEN measure_condition_components.duty_expression_id = '12' THEN 'AC'
        WHEN measure_condition_components.duty_expression_id = '14' THEN 'AC (reduced)'
        WHEN measure_condition_components.duty_expression_id = '21' THEN 'SD'
        WHEN measure_condition_components.duty_expression_id = '25' THEN 'SD (reduced)'
        WHEN measure_condition_components.duty_expression_id = '27' THEN 'FD'
        WHEN measure_condition_components.duty_expression_id = '29' THEN 'FD (reduced)'
        WHEN measure_condition_components.duty_expression_id = '99' THEN measure_condition_components.measurement_unit_code
        ELSE TO_CHAR(duty_amount, 'FM9999990D00') || (
          CASE
            WHEN monetary_unit_code IS NOT NULL THEN ' ' || monetary_unit_code
            ELSE '%'
          END
        )
      END
    ) AS amount,
    unit_codes.description AS unit,
    unit_qualifiers.description AS qualifier
  FROM
    measure_condition_components
  LEFT OUTER JOIN duty_expression_prefixes ON duty_expression_prefixes.duty_expression_id = measure_condition_components.duty_expression_id
  LEFT OUTER JOIN unit_codes ON measure_condition_components.measurement_unit_code = unit_codes.unit_code
  LEFT OUTER JOIN unit_qualifiers ON measure_condition_components.measurement_unit_qualifier_code = unit_qualifiers.unit_qualifier_code
  ORDER BY
    measure_condition_sid ASC,
    measure_condition_components.duty_expression_id ASC
),
condition_component_expressions AS (
  SELECT
    measure_condition_sid,
    string_agg(
        CONCAT(
            (CASE WHEN prefix IS NOT NULL THEN prefix || ' ' ELSE '' END),
            amount,
            (CASE WHEN unit IS NOT NULL THEN ' / ' || unit ELSE '' END),
            (CASE WHEN qualifier IS NOT NULL THEN ' / ' || qualifier ELSE '' END)
        ), ' '
        ORDER BY condition_component_parts.duty_expression_id ASC
    ) AS duty_expression
  FROM
    condition_component_parts
  GROUP BY
    condition_component_parts.measure_condition_sid
  ORDER BY
    condition_component_parts.measure_condition_sid ASC
),
-- We now combine these into a full entry price expression.
-- This is a "import price greater than" value + a duty expression that applies.
entry_price_system_parts AS (
  SELECT
    measure_sid,
    component_sequence_number,
    (
      TO_CHAR(condition_duty_amount, 'FM9999990D00') || (
        CASE
          WHEN condition_monetary_unit_code IS NOT NULL THEN ' ' || condition_monetary_unit_code
          ELSE '%'
        END
      )
    ) AS amount,
    unit_codes.description AS unit,
    unit_qualifiers.description AS qualifier,
    condition_component_expressions.duty_expression AS duty_expression
  FROM
    measure_conditions
  LEFT OUTER JOIN unit_codes ON measure_conditions.condition_measurement_unit_code = unit_codes.unit_code
  LEFT OUTER JOIN unit_qualifiers ON measure_conditions.condition_measurement_unit_qualifier_code = unit_qualifiers.unit_qualifier_code
  LEFT OUTER JOIN condition_component_expressions ON measure_conditions.measure_condition_sid = condition_component_expressions.measure_condition_sid
    ORDER BY
    measure_sid ASC,
    component_sequence_number ASC
),
entry_price_system_expressions AS (
  SELECT
    measure_sid,
    string_agg(
      CONCAT(
        '(≥ ',
        amount,
        (CASE WHEN unit IS NOT NULL THEN ' / ' || unit ELSE '' END),
        (CASE WHEN qualifier IS NOT NULL THEN ' / ' || qualifier ELSE '' END),
        '): ',
        duty_expression
      ),
      E';\n' -- join with a newline
      ORDER BY component_sequence_number ASC
    ) AS entry_price_expression
  FROM entry_price_system_parts
  GROUP BY
    measure_sid
  ORDER BY
    measure_sid ASC
),
-- Join on to legislation to get the real end dates of measures.
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
  (CASE
    WHEN duty_expressions.duty_expression IS NOT NULL THEN duty_expressions.duty_expression
    ELSE entry_price_system_expressions.entry_price_expression
  END) AS "Duty expression",
  geo_descriptions.geographical_area_id AS "Origin ID",
  geo_descriptions.description AS "Origin",
  measures.ordernumber AS "Quota #",
  measures.validity_start_date::date AS "Start date",
  measures.real_end_date::date AS "End date"
FROM
  goods_nomenclatures
  LEFT OUTER JOIN measures_mapping AS goods_measures ON goods_nomenclatures.goods_nomenclature_sid = goods_measures.sid
  LEFT OUTER JOIN goods_indents ON goods_nomenclatures.goods_nomenclature_sid = goods_indents.goods_nomenclature_sid
  LEFT OUTER JOIN real_measures AS measures ON goods_measures.measure_sid = measures.measure_sid
  LEFT OUTER JOIN duty_expressions ON measures.measure_sid = duty_expressions.measure_sid
  LEFT OUTER JOIN entry_price_system_expressions ON measures.measure_sid = entry_price_system_expressions.measure_sid
  LEFT OUTER JOIN goods_descriptions ON goods_descriptions.goods_nomenclature_sid = goods_nomenclatures.goods_nomenclature_sid
  LEFT OUTER JOIN geo_descriptions ON measures.geographical_area_sid = geo_descriptions.geographical_area_sid
  LEFT OUTER JOIN measure_type_descriptions ON measures.measure_type_id = measure_type_descriptions.measure_type_id
ORDER BY
  goods_nomenclatures.goods_nomenclature_item_id,
  goods_indents.number_indents,
  geo_descriptions.geographical_area_id