CREATE OR REPLACE FUNCTION ml.goods_nomenclature_export_generic(pchapter character varying, key_date character varying)
 RETURNS TABLE(goods_nomenclature_sid integer, goods_nomenclature_item_id character varying, producline_suffix character varying, validity_start_date timestamp without time zone, validity_end_date timestamp without time zone, description text, number_indents integer, goods_nomenclature_description_period_sid integer, nice_description text, hs_chapter text, hs_section integer, node text, leaf text, significant_digits integer)
 LANGUAGE plpgsql
AS $function$

#variable_conflict use_column

DECLARE key_date2 date := key_date::date;

BEGIN

	
IF pchapter = '' THEN
pchapter = '%';
END IF;

/* temporary table contains results of query plus a placeholder column for leaf - defaulted to 0
node column has the significant digits used to find child nodes having the same significant digits.
The basic query retrieves all current (and future) nomenclature with indents and descriptions */

DROP TABLE IF EXISTS tmp_nomenclature;

CREATE TEMP TABLE tmp_nomenclature ON COMMIT DROP AS
SELECT gn.goods_nomenclature_sid, gn.goods_nomenclature_item_id, gn.producline_suffix, gn.validity_start_date, gn.validity_end_date, 
regexp_replace(gnd.description, E'[\\n\\r]+', ' ', 'g') as description,
gni.number_indents, 
gndp.goods_nomenclature_description_period_sid,
concat(repeat ('- ', gni.number_indents), ' ', regexp_replace(gnd.description, E'[\\n\\r]+', ' ', 'g')) "nice_description",
left (gn.goods_nomenclature_item_id, 2) AS "hs_chapter",
CASE
WHEN LEFT(gn.goods_nomenclature_item_id, 2)::integer IN (1, 2, 3, 4, 5) THEN 1
WHEN LEFT(gn.goods_nomenclature_item_id, 2)::integer IN (6, 7, 8, 9, 10, 11, 12, 13, 14) THEN 2
WHEN LEFT(gn.goods_nomenclature_item_id, 2)::integer IN (15) THEN 3
WHEN LEFT(gn.goods_nomenclature_item_id, 2)::integer IN (16, 17, 18, 19, 20, 21, 22, 23, 24) THEN 4
WHEN LEFT(gn.goods_nomenclature_item_id, 2)::integer IN (25, 26, 27) THEN 5
WHEN LEFT(gn.goods_nomenclature_item_id, 2)::integer IN (28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38) THEN 6
WHEN LEFT(gn.goods_nomenclature_item_id, 2)::integer IN (39, 40) THEN 7
WHEN LEFT(gn.goods_nomenclature_item_id, 2)::integer IN (41, 42, 43) THEN 8
WHEN LEFT(gn.goods_nomenclature_item_id, 2)::integer IN (44, 45, 46) THEN 9
WHEN LEFT(gn.goods_nomenclature_item_id, 2)::integer IN (47, 48, 49) THEN 10
WHEN LEFT(gn.goods_nomenclature_item_id, 2)::integer IN (50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63) THEN 11
WHEN LEFT(gn.goods_nomenclature_item_id, 2)::integer IN (64, 65, 66, 67) THEN 12
WHEN LEFT(gn.goods_nomenclature_item_id, 2)::integer IN (68, 69, 70) THEN 13
WHEN LEFT(gn.goods_nomenclature_item_id, 2)::integer IN (71) THEN 14
WHEN LEFT(gn.goods_nomenclature_item_id, 2)::integer IN (72, 73, 74, 75, 76, 77, 78, 79, 80, 81, 82, 83) THEN 15
WHEN LEFT(gn.goods_nomenclature_item_id, 2)::integer IN (84, 85) THEN 16
WHEN LEFT(gn.goods_nomenclature_item_id, 2)::integer IN (86, 87, 88, 89) THEN 17
WHEN LEFT(gn.goods_nomenclature_item_id, 2)::integer IN (90, 91, 92) THEN 18
WHEN LEFT(gn.goods_nomenclature_item_id, 2)::integer IN (93) THEN 19
WHEN LEFT(gn.goods_nomenclature_item_id, 2)::integer IN (94, 95, 96) THEN 20
WHEN LEFT(gn.goods_nomenclature_item_id, 2)::integer IN (97, 98) THEN 21
END As "hs_section",
REGEXP_REPLACE (gn.goods_nomenclature_item_id, '(00)+$', '') AS "node",
'0' AS "leaf",
CASE
WHEN RIGHT(gn.goods_nomenclature_item_id, 8) = '00000000' THEN 2
WHEN RIGHT(gn.goods_nomenclature_item_id, 6) = '000000' THEN 4
WHEN RIGHT(gn.goods_nomenclature_item_id, 4) = '0000' THEN 6
WHEN RIGHT(gn.goods_nomenclature_item_id, 2) = '00' THEN 8
ELSE 10
END As significant_digits
FROM goods_nomenclatures gn
JOIN goods_nomenclature_descriptions gnd ON gnd.goods_nomenclature_sid = gn.goods_nomenclature_sid
JOIN goods_nomenclature_description_periods gndp ON gndp.goods_nomenclature_description_period_sid = gnd.goods_nomenclature_description_period_sid
JOIN goods_nomenclature_indents gni ON gni.goods_nomenclature_sid = gn.goods_nomenclature_sid
WHERE (gn.validity_end_date IS NULL OR gn.validity_end_date > key_date2)
AND gn.goods_nomenclature_item_id LIKE pchapter
AND gndp.goods_nomenclature_description_period_sid IN
(SELECT MAX (gndp2.goods_nomenclature_description_period_sid)
FROM goods_nomenclature_description_periods gndp2
WHERE gndp2.goods_nomenclature_sid = gnd.goods_nomenclature_sid
AND gndp2.validity_start_date <= key_date2
);



/* index to speed up child node matching - need to perf test to see if any use */
CREATE INDEX t1_i_nomenclature 
ON tmp_nomenclature (goods_nomenclature_sid, goods_nomenclature_item_id);

/* cursor loops through result set to identify if nodes are leaf and updates the flag if so */
DECLARE
cur_nomenclature CURSOR FOR 
SELECT * FROM tmp_nomenclature;

BEGIN

FOR nom_record IN cur_nomenclature LOOP
Raise Notice 'goods nomenclature item id %', nom_record.goods_nomenclature_item_id;

/* Leaf nodes have to have pls of 80 and no children having the same nomenclature code */
IF nom_record.producline_suffix = '80' THEN
IF LENGTH (nom_record.node) = 10 OR NOT EXISTS (SELECT 1 
FROM tmp_nomenclature 
WHERE goods_nomenclature_item_id LIKE CONCAT(nom_record.node,'%')
AND goods_nomenclature_item_id <> nom_record.goods_nomenclature_item_id) THEN

UPDATE tmp_nomenclature tn
SET leaf = '1'
WHERE goods_nomenclature_sid = nom_record.goods_nomenclature_sid;

END IF;
END IF;

END LOOP;

END;

RETURN QUERY 
SELECT * 
FROM tmp_nomenclature;

END;

$function$
;
