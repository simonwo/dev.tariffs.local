<?php
    $title = "View certificate";
    require ("includes/db.php");
    $certificate_type_code   = get_querystring("certificate_type_code");
    $certificate_code        = get_querystring("certificate_code");
    $certificate = new certificate;
    $certificate->clear_cookies();
    require ("includes/header.php");
?>

<div id="wrapper" class="direction-ltr">
<div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
    <ol class="govuk-breadcrumbs__list">
        <li class="govuk-breadcrumbs__list-item">
            <a class="govuk-breadcrumbs__link" href="/">Home</a>
        </li>
        <li class="govuk-breadcrumbs__list-item">
            <a class="govuk-breadcrumbs__link" href="/certificates.html">Certificates</a>
        </li>
        <li class="govuk-breadcrumbs__list-item">Certificate</li>
    </ol>
    </div>
    <div class="app-content__header">
        <h1 class="govuk-heading-xl">View certificate <?=$certificate_type_code?><?=$certificate_code?></h1>
    </div>
            <!-- MENU //-->
            <h2 class="nomargin">Page content</h2>
            <ul class="tariff_menu">
                <li><a href="#details">Certificate details</a></li>
                <li><a href="#description_periods">Certificate description periods</a></li>
                <li><a href="#usage_measures">Certificate usage in measures</a></li>
            </ul>

            <h2 class="nomargin" id="details">Area details</h2>
            <table class="govuk-table" cellspacing="0">
                <tr class="govuk-table__row">
                    <th class="govuk-table__header" style="width:15%">Property</th>
                    <th class="govuk-table__header" style="width:50%">Value</th>
                </tr>
<?php
	$sql = "SELECT certificate_type_code, certificate_code, description, validity_start_date, validity_end_date
    FROM ml.ml_certificate_codes WHERE certificate_type_code = '" . $certificate_type_code . "' AND certificate_code = '" . $certificate_code . "';";
    $result = pg_query($conn, $sql);
	if  ($result) {
        $row = pg_fetch_row($result);
        $certificate_type_code       = $row[0];
        $certificate_code            = $row[1];
        $description            = $row[2];
        $validity_start_date    = $row[3];
        $validity_end_date      = $row[4];
?>
                <tr class="govuk-table__row">
                    <td class="govuk-table__cell">Certificate type / code</td>
                    <td class="govuk-table__cell b"><?=$certificate_type_code?><?=$certificate_code?></td>
                </tr>
                <tr class="govuk-table__row">
                    <td class="govuk-table__cell">Description</td>
                    <td class="govuk-table__cell tight"><?=$description?></td>
                </tr>
                <tr class="govuk-table__row">
                    <td class="govuk-table__cell">Start date</td>
                    <td class="govuk-table__cell"><?=short_date($validity_start_date)?></td>
                </tr>
                <tr class="govuk-table__row">
                    <td class="govuk-table__cell">End date</td>
                    <td class="govuk-table__cell"><?=short_date($validity_end_date)?></td>
                </tr>
<?php
    }
?>

            </table>
            <p class="back_to_top"><a href="#top">Back to top</a></p>






<h2 id="description_periods">Certificate description periods</h2>
<form action="/certificate_add_description.html" method="get" class="inline_form">
    <input type="hidden" name="phase" value="certificate_add_description" />
    <input type="hidden" name="action" value="new" />
    <input type="hidden" name="certificate_code" value="<?=$certificate_code?>" />
    <input type="hidden" name="certificate_type_code" value="<?=$certificate_type_code?>" />
    <h3>Create new certificate description</h3>
    <div class="column-one-third" style="width:320px">
    	<div class="govuk-form-group" style="padding:0px;margin:0px">
            <button type="submit" class="govuk-button">New description</button>
        </div>
    </div>
    <div class="clearer"><!--&nbsp;//--></div>
</form>

<?php
    $sql = "SELECT fd.certificate_description_period_sid, fd.certificate_type_code, fd.certificate_code,
    fdp.validity_start_date, fd.description
    FROM certificate_description_periods fdp, certificate_descriptions fd
    WHERE fdp.certificate_description_period_sid = fd.certificate_description_period_sid
    AND fd.certificate_type_code = '" . $certificate_type_code . "' AND fd.certificate_code = '" . $certificate_code . "'
    ORDER BY validity_start_date DESC";   
    $result = pg_query($conn, $sql);
	if  ($result) {
?>
        <p>The table below lists the historic and current descriptions for this certificate. You can only edit or delete descriptions
        that have not yet begun. Also, you are not able to delete the first description associated with a certificate.</p>
        <table class="govuk-table" cellspacing="0">
            <tr class="govuk-table__row">
                <th class="govuk-table__header" style="width:10%">SID</th>
                <th class="govuk-table__header" style="width:69%">Name</th>
                <th class="govuk-table__header" style="width:15%">Validity start date</th>
                <th class="govuk-table__header c" style="width:6%">Actions</th>
            </tr>
<?php
        $row_count = pg_num_rows($result);
        $i = 0;
        while ($row = pg_fetch_array($result)) {
            $i += 1;
            $certificate_description_period_sid   = $row["certificate_description_period_sid"];
            $description                                = $row["description"];
            $validity_start_date                        = short_date($row["validity_start_date"]);
            $validity_start_date2                       = string_to_date($row["validity_start_date"]);
?>
                <tr class="govuk-table__row">
                    <td class="govuk-table__cell"><?=$certificate_description_period_sid?></td>
                    <td class="govuk-table__cell"><?=$description?></td>
                    <td class="govuk-table__cell"><?=$validity_start_date?></td>
                    <td class="govuk-table__cell c">
<?php
    if (is_in_future($validity_start_date2)) {
?>
                        <form action="certificate_add_description.html" method="get">
                            <input type="hidden" name="action" value="edit" />
                            <input type="hidden" name="certificate_code" value="<?=$certificate_code?>" />
                            <input type="hidden" name="certificate_type_code" value="<?=$certificate_type_code?>" />
                            <input type="hidden" name="certificate_description_period_sid" value="<?=$certificate_description_period_sid?>" />
                            <button type="submit" class="govuk-button btn_nomargin")>Edit</button>
                        </form>
<?php
        if ($i < $row_count) {
?>
                        <form action="actions/certificate_actions.html" method="get">
                            <input type="hidden" name="action" value="edit" />
                            <input type="hidden" name="phase" value="certificate_description_delete" />
                            <input type="hidden" name="certificate_code" value="<?=$certificate_code?>" />
                            <input type="hidden" name="certificate_type_code" value="<?=$certificate_type_code?>" />
                            <input type="hidden" name="certificate_description_period_sid" value="<?=$certificate_description_period_sid?>" />
                            <button type="submit" onclick="return (are_you_sure());" class="govuk-button btn_nomargin")>Delete</button>
                        </form>
<?php
        }
    }
?>                        
				    </td>
                </tr>
<?php
        }
?>
        </table>
        <?php
    }
?>
    












            <p class="back_to_top"><a href="#top">Back to top</a></p>
            
            <h2 id="usage_measures">Certificate usage in measures</h2>
<?php
    $sql = "SELECT m.measure_sid, regulation_id_full, goods_nomenclature_item_id, m.measure_type_id,
    m.geographical_area_id, m.validity_start_date, m.validity_end_date, ordernumber,
    mtd.description as measure_type_description, ga.description as geo_description
    FROM ml.v5 m, measure_conditions mc, measure_type_descriptions mtd, ml.ml_geographical_areas ga
    WHERE m.measure_sid = mc.measure_sid
    AND m.measure_type_id = mtd.measure_type_id
    AND m.geographical_area_id = ga.geographical_area_id
    AND mc.certificate_type_code = '" . $certificate_type_code . "'
    AND mc.certificate_code = '" . $certificate_code . "'
    ORDER BY goods_nomenclature_item_id";

    $result = pg_query($conn, $sql);
    if  (($result) && (pg_num_rows($result) > 0)){
?>
            <table class="govuk-table" cellspacing="0">
                <tr class="govuk-table__row">
                    <th class="govuk-table__header" style="width:12%">SID</th>
                    <th class="govuk-table__header" style="width:12%">Commodity</th>
                    <th class="govuk-table__header" style="width:13%">Start date</th>
                    <th class="govuk-table__header" style="width:12%">End date</th>
                    <th class="govuk-table__header" style="width:13%">Geographical area</th>
                    <th class="govuk-table__header" style="width:12%">Measure type</th>
                    <th class="govuk-table__header" style="width:13%">Regulation&nbsp;ID</th>
                </tr>
<?php
        while ($row = pg_fetch_array($result)) {
            $measure_sid                = $row['measure_sid'];
            $goods_nomenclature_item_id = $row['goods_nomenclature_item_id'];
            $measure_type_id            = $row['measure_type_id'];
            $geographical_area_id       = $row['geographical_area_id'];
            $regulation_id_full         = $row['regulation_id_full'];
            $validity_start_date        = trim($row['validity_start_date'] . "");
            $validity_end_date          = trim($row['validity_end_date'] . "");
            $measure_type_description   = $row['measure_type_description'];
            $geo_description            = $row['geo_description'];
            

            $validity_start_date        = DateTime::createFromFormat('Y-m-d H:i:s', $validity_start_date)->format('Y-m-d');
            if ($validity_end_date != "") {
                $validity_end_date      = DateTime::createFromFormat('Y-m-d H:i:s', $validity_end_date)->format('Y-m-d');
            } else {
                $validity_end_date = "";
            }

            $commodity_url                  = "/goods_nomenclature_item_view.html?goods_nomenclature_item_id=" . $goods_nomenclature_item_id
?>
                <tr class="govuk-table__row">
                    <td class="govuk-table__cell"><a href="measure_view.html?measure_sid=<?=$measure_sid?>"><?=$measure_sid?></a></td>
                    <td class="govuk-table__cell"><a href="<?=$commodity_url?>" data-lity data-lity-target="<?=$commodity_url?>?>"><?=$goods_nomenclature_item_id?></a></td>
                    <td class="govuk-table__cell"><?=$validity_start_date?></td>
                    <td class="govuk-table__cell"><?=$validity_end_date?></td>
                    <td class="govuk-table__cell"><a href="geographical_area_view.html?geographical_area_id=<?=$geographical_area_id?>"><?=$geographical_area_id?>&nbsp;<?=$geo_description?></a></td>
                    <td class="govuk-table__cell"><a href="measure_type_view.html?measure_type_id=<?=$measure_type_id?>"><?=$measure_type_id?>&nbsp;<?=$measure_type_description?></a></td>
                    <td class="govuk-table__cell"><a href="regulation_view.html?regulation_id=<?=$regulation_id_full?>"><?=$regulation_id_full?></a></td>
                </tr>

<?php
        }
?>
            </table>
<?php
    } else {
        echo ("<p>There are no associations of this foonote with measures.");
    }
?>
            <p class="back_to_top"><a href="#top">Back to top</a></p>
</div>

<?php
    require ("includes/footer.php")
?>