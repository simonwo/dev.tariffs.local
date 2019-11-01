<?php
    $title = "Delete measure";
	require ("includes/db.php");
	require ("includes/header.php");
    $measure_sid                = get_querystring("measure_sid");
    $goods_nomenclature_item_id = get_querystring("goods_nomenclature_item_id");
    $geographical_area_id       = get_querystring("geographical_area_id");
?>
<div id="wrapper" class="direction-ltr">
	<!-- Start breadcrumbs //-->
	<div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
		<ol class="govuk-breadcrumbs__list">
			<li class="govuk-breadcrumbs__list-item">
				<a class="govuk-breadcrumbs__link" href="/">Main menu</a>
			</li>
			<li class="govuk-breadcrumbs__list-item">Measures</li>
		</ol>
	</div>
	<!-- End breadcrumbs //-->

    <div class="panel panel--confirmation">
        <h1 class="heading-xlarge" style="width:1800px !important">Measure deleted</h1>
        <p class="heading-medium">Measure <?=$measure_sid?> has been deleted.</p>
        <p>View more <a href="/goods_nomenclature_item_view.html?goods_nomenclature_item_id=<?=$goods_nomenclature_item_id?>#assigned">measures for this commodity code (<?=$goods_nomenclature_item_id?>)</a></p>
        <p>View more <a href="/geographical_area_view.html?geographical_area_id=<?=$geographical_area_id?>#measures">measures for this geographical area (<?=$geographical_area_id?>)</a></p>
        <p><a href="/measures.html">Search for measures</a></p>
    </div>


</div>

<?php
	require ("includes/footer.php")
?>