<?php
    global $last_exported_operation_date;
    $title = "Data extract";
    require ("includes/db.php");
    require ("includes/header.php");
    $extract = new extract();
    $extract->set_parameters()
?>
<div id="wrapper" class="direction-ltr">
    <!-- Start breadcrumbs //-->
    <div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
        <ol class="govuk-breadcrumbs__list">
            <li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/">Main menu</a></li>
            <li class="govuk-breadcrumbs__list-item">Data extract</li>
        </ol>
    </div>
    <!-- End breadcrumbs //-->
    <div class="app-content__header">
        <h1 class="govuk-heading-xl">Data extract</h1>
    </div>


<form action="/actions/extract_actions.html" method="get" class="inline_form">
    <input type="hidden" name="phase" value="extract_data" />
    <h3>Extract latest changes</h3>
    <p>This will extract all data that has been changed since <strong><?=$last_exported_operation_date;?></strong>.<br /><br />Click to <a href="/extract_date_set.html">modify the extract date</a>.</p>
    <div class="column-one-third" style="width:320px">
	<div class="govuk-form-group" style="padding:0px;margin:0px">
            <button type="submit" class="govuk-button">Extract changes</button>
        </div>
    </div>
    <div class="clearer"><!--&nbsp;//--></div>
</form>

</div>

<?php
    require ("includes/footer.php")
?>