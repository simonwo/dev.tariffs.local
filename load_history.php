<?php
    require ("includes/db.php");
    require ("includes/header.php");
    $section_id = get_querystring("section_id");
?>
<div id="wrapper" class="direction-ltr">
    <!-- Start breadcrumbs //-->
    <div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
        <ol class="govuk-breadcrumbs__list">
            <li class="govuk-breadcrumbs__list-item">
                <a class="govuk-breadcrumbs__link" href="/">Home</a>
            </li>
            <li class="govuk-breadcrumbs__list-item">
                Load history
            </li>
        </ol>
    </div>
    <!-- End breadcrumbs //-->
    <div class="app-content__header">
        <h1 class="govuk-heading-xl">Load history</h1>
    </div>
    <table cellspacing="0" class="govuk-table">
        <tr class="govuk-table__row">
            <th class="govuk-table__header" style="width:56%">File</th>
            <!--
            <th class="govuk-table__header" style="width:48%">File</th>
            <th class="govuk-table__header" style="width:8%">Size</th>
            //-->
            <th class="govuk-table__header" style="width:16%">Start time</th>
            <th class="govuk-table__header" style="width:16%">Completion time</th>
            <th class="govuk-table__header c" style="width:8%">Actions</th>
        </tr>

<?php
    $sql = "SELECT * FROM ml.import_files ORDER BY import_completed DESC, import_file";
    $result = pg_query($conn, $sql);
	if  ($result) {
        while ($row = pg_fetch_array($result)) {
            $import_file        = $row['import_file'];
            $import_started     = $row['import_started'];
            $import_completed   = $row['import_completed'];
            $path = "xml/";
            $file = $path . $import_file;
            if (file_exists($file)) {
                try {
                    $file_size = filesize($file);
                }
                catch (customException $e){
                    $file_size = 0;
                }
            } else {
                $file_size = 0;
            }
?>
        <tr class="govuk-table__row">
            <!--
            <td class="govuk-table__cell"><a href="xml_load.html?file=<?=$import_file?>"><?=$import_file?></a></td>
            <td class="govuk-table__cell"><?=$file_size?> kb</a></td>
            //-->
            <td class="govuk-table__cell"><?=$import_file?></td>
            <td class="govuk-table__cell"><?=$import_started?></a></td>
            <td class="govuk-table__cell"><?=$import_completed?></a></td>
            <td class="govuk-table__cell c">
<?php
    if ($import_started != "") {
?>
                <form action="/actions/rollback_actions.html" method="get">
                    <input type="hidden" name="phase" value="perform_rollback" />
                    <input type="hidden" name="import_file" value="<?=$import_file?>" />
                    <input type="hidden" name="import_started" value="<?=$import_started?>" />
                    <button type="submit" class="govuk-button btn_nomargin")>Roll back</button>
                </form>
<?php
            } else { echo ("&nbsp;"); }
?>
            </td>
        </tr>
<?php
        }
    }
?>
        </table>
</div>

<?php
    require ("includes/footer.php")
?>