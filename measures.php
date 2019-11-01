<?php
    $title = "Measures";
    require ("includes/db.php");
    require ("includes/header.php");
    $phase = "measure_search";
?>
<div id="wrapper" class="direction-ltr">
    <div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
        <ol class="govuk-breadcrumbs__list">
            <li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/">Main menu</a></li>
            <li class="govuk-breadcrumbs__list-item">Find measures</li>
        </ol>
    </div>
    <div class="app-content__header">
        <h1 class="govuk-heading-xl">Find measures</h1>
    </div>
<?php
    require ("includes/measure_search.php");
?>
</div>
<?php
    require ("includes/footer.php")
?>