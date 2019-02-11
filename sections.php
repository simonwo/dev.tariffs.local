<?php
    require ("includes/db.php");
    require ("includes/header.php");
    ?>
<div id="wrapper" class="direction-ltr">
    <div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
        <ol class="govuk-breadcrumbs__list">
            <li class="govuk-breadcrumbs__list-item">
                <a class="govuk-breadcrumbs__link" href="/">Home</a>
            </li>
            <li class="govuk-breadcrumbs__list-item">
                Nomenclature sections
            </li>
        </ol>
    </div>
    <main id="content" lang="en">
        <div class="grid-row">
            <div class="column-two-thirds">
                <div class="gem-c-title gem-c-title--margin-bottom-5">
                    <h1 class="gem-c-title__text">Nomenclature sections</h1>
                </div>
            </div>
        </div>
        <div class="clearer"><!-- &nbsp; //--></div>
        <table cellspacing="0" class="noborder tbl">
            <tr class="govuk-table__row">
                <th class="govuk-table__header" style="width:15%">Section</th>
                <th class="govuk-table__header" style="width:85%">Description</th>
            </tr>

<?php
    $sql = "SELECT id as section_id, numeral, title FROM sections ORDER BY id";
    $result = pg_query($conn, $sql);
	if  ($result) {
        while ($row = pg_fetch_array($result)) {
            $section_id = $row['section_id'];
            $numeral    = $row['numeral'];
            $title      = $row['title'];
?>
                <tr class="govuk-table__row">
                    <td class="govuk-table__cell"><a href="chapter.php?section_id=<?=$section_id?>">Section <?=$numeral?></a></td>
                    <td class="govuk-table__cell"><?=$title?></td>
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