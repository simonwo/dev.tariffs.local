-- This file outputs all of the commodities in a TARIC3-style database.
-- Each commodity code will appear once alongside their parent code.

-- The file uses temporary tables and is designed to be run in
-- a transaction. A number of PostgreSQL-specific features are used.

-- (c) Simon Worthington 2020, Department for International Trade
-- Released under the Open Government Licence v3:
-- https://www.nationalarchives.gov.uk/doc/open-government-licence/version/3/

-- We create a mapping of CCs to the next CC with the same indent
-- that we will use later to work out parents.
-- We do this using a window function by ordering by CC and then
-- selecting the next row with the same indent.
-- We do this as a temporary table rather than a CTE so that we can
-- put some indexes on it.
CREATE TEMPORARY TABLE ccs (
  cc char(10),
  sid int4,
  indent integer,
  next char(10),
  next_suffix char(2),
  next_sid int4,
  producline_suffix char(2)
) ON COMMIT DROP;

CREATE INDEX ON ccs (producline_suffix, cc);

CREATE INDEX ON ccs (cc);

CREATE INDEX ON ccs (next);

CREATE INDEX ON ccs (sid);

CREATE INDEX ON ccs (next_sid);

-- Note that indent is bad and terrible – it is almost but not quite
-- the number of levels down the tree a code is, except at the top level
-- where the first two levels are both zero. This belies it's actual use
-- which is to control how many indents are inserted in a document to give
-- a visual tree structure, and chapter headings are not indented.
-- So we fix this by setting chapters (HS2) to -1 to create the right structure.
WITH parents AS (
  SELECT
    parents.*,
    (
      CASE
        WHEN parents.goods_nomenclature_item_id LIKE '%00000000' THEN -1
        ELSE parent_indents.number_indents
      END
    ) AS number_indents
  FROM
    goods_nomenclatures AS parents
    LEFT OUTER JOIN goods_nomenclature_indents AS parent_indents ON parents.goods_nomenclature_sid = parent_indents.goods_nomenclature_sid
)
INSERT INTO
  ccs
SELECT
  parents.goods_nomenclature_item_id as cc,
  parents.goods_nomenclature_sid AS sid,
  parents.number_indents as indent,
  LEAD(parents.goods_nomenclature_item_id) OVER (
    PARTITION BY parents.number_indents
    ORDER BY
      parents.goods_nomenclature_item_id
  ) AS next,
  LEAD(parents.producline_suffix) OVER (
    PARTITION BY parents.number_indents
    ORDER BY
      parents.goods_nomenclature_item_id
  ) AS next_suffix,
  LEAD(parents.goods_nomenclature_sid) OVER (
    PARTITION BY parents.number_indents
    ORDER BY
      parents.goods_nomenclature_item_id
  ) AS next_sid,
  parents.producline_suffix AS producline_suffix
FROM
  parents;

-- Now we create the mapping of CCs to all descendent CCs.
-- We do this using the table above, selecting all of the CCs
-- that exist between the two limits and at a greater indent.
-- Again we do this as a temporary table so we can take advantage
-- of indexes.
CREATE TEMPORARY TABLE cc_children (
  parent_sid int4,
  parent_cc char(10),
  parent_suffix char(2),
  parent_indent integer,
  child_sid int4,
  child_cc char(10),
  child_suffix char(2),
  child_indent integer
) ON COMMIT DROP;

CREATE INDEX ON cc_children (parent_sid);

CREATE INDEX ON cc_children (child_sid);

INSERT INTO
  cc_children
SELECT
  cc1.sid as parent_sid,
  cc1.cc as parent_cc,
  cc1.producline_suffix as parent_suffix,
  cc1.indent as parent_indent,
  cc2.sid as child_sid,
  cc2.cc as child_cc,
  cc2.producline_suffix as child_suffix,
  cc2.indent as child_indent
FROM
  ccs as cc1,
  ccs as cc2
WHERE
  cc2.cc > cc1.cc
  AND cc2.cc < cc1.next
  AND cc2.producline_suffix <= cc1.next_suffix;

-- The start of the actual query. The first two tables link each code
-- to the most recent description and indent, both of which can change
-- independently. We also take the opportunity to find the number of
-- trailing zeroes in the commodity code, which tells us the HS level.
WITH goods_descriptions AS (
  SELECT
    *,
    CHAR_LENGTH(
      TRIM( TRAILING '0' FROM goods_nomenclature_item_id )
    ) :: integer AS trailing_zeroes
  FROM
    (
      SELECT
        g.goods_nomenclature_sid,
        g.goods_nomenclature_item_id,
        gd.description,
        ROW_NUMBER() OVER (
          PARTITION BY g.goods_nomenclature_sid
          ORDER BY
            gdp.validity_start_date DESC
        ) AS row
      FROM
        goods_nomenclatures_oplog AS g
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
)
SELECT
  goods_nomenclatures.goods_nomenclature_sid :: integer AS "SID",
  goods_nomenclatures.goods_nomenclature_item_id AS "Commodity code",
  goods_nomenclatures.producline_suffix :: integer AS "Suffix",
  goods_indents.number_indents :: integer AS "Indent",
  -- Round up the trailing zeros to the nearest multiple of 2 – that's the HS level.
  ( goods_descriptions.trailing_zeroes + MOD(goods_descriptions.trailing_zeroes, 2) ) :: integer AS "HS Level",
  goods_descriptions.description AS "Description",
  goods_parents.parent_sid :: integer AS "Parent SID",
  goods_parents.parent_cc AS "Parent code",
  goods_parents.parent_suffix :: integer AS "Parent suffix",
  -- Fix the -1 in chapter headings back to their "correct" zero.
  GREATEST(0, goods_parents.parent_indent) :: integer AS "Parent indent"
FROM
  goods_nomenclatures
  LEFT OUTER JOIN goods_indents ON goods_nomenclatures.goods_nomenclature_sid = goods_indents.goods_nomenclature_sid
  LEFT OUTER JOIN goods_descriptions ON goods_descriptions.goods_nomenclature_sid = goods_nomenclatures.goods_nomenclature_sid
  LEFT OUTER JOIN cc_children AS goods_parents ON goods_nomenclatures.goods_nomenclature_sid = goods_parents.child_sid
WHERE
  -- Only select comm codes which are "current".
  (
    goods_nomenclatures.validity_end_date IS NULL
    or goods_nomenclatures.validity_end_date >= NOW()
  )
  -- Only select parents that actually a parent (the ccs table
  -- also contains higher level ancestors, e.g. grandparents)
  AND (
    goods_parents.parent_indent = goods_indents.number_indents - 1
    OR goods_parents.parent_sid IS NULL
  )
ORDER BY
  goods_nomenclatures.goods_nomenclature_item_id,
  goods_indents.number_indents
