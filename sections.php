<?php
    $title = "Sections";
    require ("includes/db.php");
    require ("includes/header.php");
    $phase = "goods_nomenclature_item_view";
    ?>
<div id="wrapper" class="direction-ltr">
    <div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
        <ol class="govuk-breadcrumbs__list">
            <li class="govuk-breadcrumbs__list-item">
                <a class="govuk-breadcrumbs__link" href="/">Main menu</a>
            </li>
            <li class="govuk-breadcrumbs__list-item">
                Manage goods classification
            </li>
        </ol>
    </div>
    <div class="app-content__header">
        <h1 class="govuk-heading-xl">Manage goods classification</h1>
    </div>
<?php
    require ("includes/nomenclature_search.php");
?>
        <table cellspacing="0" class="govuk-table">
            <tr class="govuk-table__row">
                <th class="govuk-table__header" style="width:5%">Section</th>
                <th class="govuk-table__header" style="width:8%">Chapters</th>
                <th class="govuk-table__header" style="width:87%">Description</th>
                <!--<th class="govuk-table__header r" style="width:10%">&nbsp;</th>//-->
            </tr>

<?php
    # Get the sections
    $sections = array();
    $sql = "SELECT id as section_id, numeral, title FROM sections ORDER BY id";
    $result = pg_query($conn, $sql);
	if  ($result) {
        while ($row = pg_fetch_array($result)) {
            $section_id = $row['section_id'];
            $numeral    = $row['numeral'];
            $title      = $row['title'];
            $section = new section($section_id, $numeral, $title);
            array_push ($sections, $section);
        }
    }
    # Get the chapters to sections
    $sql = "select left(gn.goods_nomenclature_item_id, 2) as chapter, section_id
    from chapters_sections cs, goods_nomenclatures gn
    where cs.goods_nomenclature_sid = gn.goods_nomenclature_sid
    order by section_id, gn.goods_nomenclature_item_id;";
    $chapters = array(21);
    $result = pg_query($conn, $sql);
	if  ($result) {
        while ($row = pg_fetch_array($result)) {
            $chapter = $row['chapter'];
            $section_id = $row['section_id'];
            foreach ($sections as $section) {
                if ($section_id == $section->section_id) {
                    array_push($section->chapters, $chapter);
                }
            }
        }
    }

    # Get the sections

    foreach ($sections as $section) {
        $section->get_chapter_string();

        ?>
            <tr class="govuk-table__row">
                <td class="govuk-table__cell"><?=$section->numeral?></td>
                <td class="govuk-table__cell"><?=$section->chapter_string?></td>
                <td class="govuk-table__cell"><a href="chapter.html?section_id=<?=$section->section_id?>"><?=$section->title?></a></td>
                <!--<td class="govuk-table__cell r"><a href="#">Extract</a></td>//-->
            </tr>
<?php
    }
?>
        </table>
</div>
<p>Extract commodity tree:&nbsp;&nbsp;
    <a href="goods_nomenclature_extract.html?depth=4">HS4</a>&nbsp;
    <a href="goods_nomenclature_extract.html?depth=6">HS6</a>&nbsp;
    <a href="goods_nomenclature_extract.html?depth=8">CN8</a>&nbsp;
    <a href="goods_nomenclature_extract.html?depth=10">CN10</a>
<?php
    require ("includes/footer.php")
?>