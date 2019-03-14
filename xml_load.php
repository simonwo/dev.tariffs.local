<?php
	require ("includes/db.php");
    require ("includes/header.php");
    
    $file   = get_querystring("file");
    $xml    = simplexml_load_file("xml/" . $file);

    $xml->registerXPathNamespace("env", "urn:publicid:-:DGTAXUD:GENERAL:ENVELOPE:1.0");
    $xml->registerXPathNamespace("oub", "urn:publicid:-:DGTAXUD:TARIC:MESSAGE:1.0");
?>
<div id="wrapper" class="direction-ltr">
	<div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
		<ol class="govuk-breadcrumbs__list">
			<li class="govuk-breadcrumbs__list-item">
				<a class="govuk-breadcrumbs__link" href="/load_history.html">Load history</a>
			</li>
			<li class="govuk-breadcrumbs__list-item">File <?=$file?></li>
		</ol>
	</div>

	<div class="app-content__header">
		<h1 class="govuk-heading-xl">Load manifest <?=$file?></h1>
	</div>

<h2>Index</h2>
<ul class="tariff_menu">
<?php
    xml_count("footnote_types", "Footnote types", "//oub:footnote.type/../../oub:record", $xml);
    xml_count("certificate_types", "Certificate types", "//oub:certificate.type/../../oub:record", $xml);
    xml_count("additional_code_types", "Additional code types", "//oub:additional.code.type/../../oub:record", $xml);
    xml_count("regulation_groups", "Regulation groups", "//oub:regulation.group/../../oub:record", $xml);
    xml_count("footnotes", "Footnotes", "//oub:footnote/../../oub:record", $xml);
    xml_count("certificates", "Certificates", "//oub:certificate/../../oub:record", $xml);
    xml_count("measure_types", "Measure types", "//oub:measure.type/../../oub:record", $xml);
    xml_count("geographical_area_descriptions", "Geographical area descriptions", "//oub:geographical.area.description/../../oub:record", $xml);
    xml_count("quota_order_numbers", "Quota order numbers", "//oub:quota.order.number/../../oub:record", $xml);
    xml_count("quota_definitions", "Quota definition", "//oub:quota.definition/../../oub:record", $xml);
    xml_count("base_regulations", "Base regulations", "//oub:base.regulation/../../oub:record", $xml);
    xml_count("goods_nomenclatures", "Goods nomenclature", "//oub:goods.nomenclature/../../oub:record", $xml);
    xml_count("measures", "Measures", "//oub:measure/../../oub:record", $xml);
?>
</ul>
<?php

// Get new goods nomenclatures
$nodes = $xml->xpath("//oub:goods.nomenclature/../../oub:record");
if (count($nodes) > 0) {
    print ("<h2 id='goods_nomenclatures'>" . count($nodes) . " goods nomenclature records</h2>");
}
$i = 1;
foreach ($nodes as $node) {
    $content = $node->children('urn:publicid:-:DGTAXUD:TARIC:MESSAGE:1.0');
    xml_head("Goods nomenclature", $i);

    $update_type    = update_type($content->{'update.type'});
    $sid            = $content->{'goods.nomenclature'}->{'goods.nomenclature.sid'};
    $id             = $content->{'goods.nomenclature'}->{'goods.nomenclature.item.id'};
    $suffix         = $content->{'goods.nomenclature'}->{'producline.suffix'};
    $start_date     = $content->{'goods.nomenclature'}->{'validity.start.date'};
    $url            = '<a href="/goods_nomenclature_item_view.html?goods_nomenclature_item_id=' . $id . '&productline_suffix=' . $suffix . '">' . $id . '</a>';

    xml_item ('Update type',    $update_type);
    xml_item ('SID',            $sid);
    xml_item ('ID',             '<a href="/goods_nomenclature_item_view.html?goods_nomenclature_item_id=' . $content->{'goods.nomenclature'}->{'goods.nomenclature.item.id'} . '">' . $content->{'goods.nomenclature'}->{'goods.nomenclature.item.id'} . '</a>');
    xml_item ('Suffix',         $suffix);
    xml_item ('Start date',     $start_date);
    xml_foot();
    $i++;
}

// Get new measures
$nodes = $xml->xpath("//oub:measure/../../oub:record");
if (count($nodes) > 0) {
    print ("<h2 id='measures'>" . count($nodes) . " measure records</h2>");
}
$i = 1;
foreach ($nodes as $node) {
    $content = $node->children('urn:publicid:-:DGTAXUD:TARIC:MESSAGE:1.0');
    xml_head("Measure", $i);

    $update_type            = update_type($content->{'update.type'});
    $sid                    = $content->{'measure'}->{'measure.sid'};
    $measure_type           = $content->{'measure'}->{'measure.type'};
    $id                     = $content->{'measure'}->{'goods.nomenclature.item.id'};
    $geographical_area      = $content->{'measure'}->{'geographical.area'};
    $start_date             = $content->{'measure'}->{'validity.start.date'};
    $end_date               = $content->{'measure'}->{'validity.end.date'};
    $regulation             = $content->{'measure'}->{'measure.generating.regulation.id'};
    $url_measure            = '<a href="/measure_view.html?measure_sid=' . $sid . '">' . $sid . '</a>';
    $url_measure_type       = '<a href="/measure_type_view.html?measure_type_id=' . $measure_type . '">' . $measure_type . '</a>';
    $url_geo                = '<a href="/geographical_area_view.html?geographical_area_id=' . $geographical_area . '">' . $geographical_area . '</a>';
    $url_regulation         = '<a href="/regulation_view.html?regulation_id=' . $regulation . '">' . $regulation . '</a>';

    xml_item ('Update type',    $update_type);
    xml_item ('SID',            $url_measure);
    xml_item ('Measure type',   $url_measure_type);
    xml_item ('Geography',      $url_geo);
    xml_item ('Start date',     $start_date);
    xml_item ('End date',       $end_date);
    xml_item ('Regulation',     $url_regulation);
    xml_foot();
    $i++;
}

// Get new base regulations
$nodes = $xml->xpath("//oub:base.regulation/../../oub:record");
if (count($nodes) > 0) {
    print ("<h2 id='base_regulations'>" . count($nodes) . " base regulation records</h2>");
}
$i = 1;
foreach ($nodes as $node) {
    $content = $node->children('urn:publicid:-:DGTAXUD:TARIC:MESSAGE:1.0');
    xml_head("Base regulation", $i);

    $update_type            = update_type($content->{'update.type'});
    $id                     = $content->{'base.regulation'}->{'base.regulation.id'};
    $group_id               = $content->{'base.regulation'}->{'regulation.group.id'};
    $start_date             = $content->{'base.regulation'}->{'validity.start.date'};
    $end_date               = $content->{'base.regulation'}->{'validity.end.date'};
    $info_text              = $content->{'base.regulation'}->{'information.text'};
    $url_regulation         = '<a href="/regulation_view.html?regulation_sid=' . $id . '">' . $id . '</a>';
    $url_group              = '<a href="/regulation_group_view.html?regulation_group_id=' . $group_id . '">' . $group_id . '</a>';
    $info_split             = explode("|", $info_text);

    xml_item ('Update type',        $update_type);
    xml_item ('Regulation',         $url_regulation);
    xml_item ('Group',              $url_group);
    xml_item ('Measure type',       $url_measure_type);
    if (count($info_split) == 3) {
        xml_item ('Publicly displayed', $info_split[0]);
        xml_item ('URL',                $info_split[1]);
        xml_item ('Information text',   $info_split[2]);
    } else {
        xml_item ('Information text',   $info_text);
    }
    xml_item ('Start date',         $start_date);
    xml_item ('End date',           $end_date);
    xml_foot();
    $i++;
}


?>
</div>
<?php
require ("includes/footer.php")
?>
