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
        </tr>

<?php
    $sql = "SELECT * FROM ml.import_files ORDER BY 1";
    $result = pg_query($conn, $sql);
	if  ($result) {
        while ($row = pg_fetch_array($result)) {
            $import_file = $row['import_file'];
?>
        <tr class="govuk-table__row">
            <td class="govuk-table__cell"><?=$import_file?></td>
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