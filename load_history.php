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
            <th class="govuk-table__header">File</th>
            <th class="govuk-table__header">Size</th>
        </tr>

<?php
    $sql = "SELECT * FROM ml.import_files ORDER BY 1";
    $result = pg_query($conn, $sql);
	if  ($result) {
        while ($row = pg_fetch_array($result)) {
            $import_file = $row['import_file'];
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
            <td class="govuk-table__cell"><a href="xml_load.php?file=<?=$import_file?>"><?=$import_file?></a></td>
            <td class="govuk-table__cell"><?=number_format($file_size)?> kb</a></td>
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