<?php
    $title = "Data extract - Set date";
    require ("includes/db.php");
    require ("includes/header.php");
    $extract = new extract();
?>
<div id="wrapper" class="direction-ltr">
    <!-- Start breadcrumbs //-->
    <div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
        <ol class="govuk-breadcrumbs__list">
            <li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/">Main menu</a></li>
            <li class="govuk-breadcrumbs__list-item">Data extract - Set date</li>
        </ol>
    </div>
    <!-- End breadcrumbs //-->
    <div class="app-content__header">
        <h1 class="govuk-heading-xl">Data extract - Set date</h1>
    </div>

<?php
    global $conn;
    $sql = "select last_exported_operation_date, last_transaction_id from ml.config;";
    $result = pg_query($conn, $sql);
	if  ($result) {
        $row = pg_fetch_row($result);
        $last_exported_operation_date   = strtotime($row[0]);
        $last_transaction_id            = $row[1];

        $day = date('d', $last_exported_operation_date);
        $month = date('m', $last_exported_operation_date);
        $year = date('Y', $last_exported_operation_date);
        $hour = date('H', $last_exported_operation_date);
        $minute = date('i', $last_exported_operation_date);
    }

?>


<form class="tariff" method="get" action="/actions/extract_actions.html">
<input type="hidden" name="phase" id="phase" value="extract_date_set" />
<!-- Begin validity start date fields //-->
<div class="govuk-form-group">
	<fieldset class="govuk-fieldset" aria-describedby="extract_hint" role="group">
		<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
			<h1 id="heading_extract_date" class="govuk-fieldset__heading" style="max-width:100%;">Extract date</h1>
		</legend>
        <span id="extract_hint" class="govuk-hint">Please enter the date after which data will be considered for extract.
            If you are happy with this date, click here to <a href="/data_extract.html">begin the data extract</a>.
        </span>
		<div class="govuk-date-input" id="validity_start">
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="extract_day">Day</label>
					<input value="<?=$day?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="extract_day" maxlength="2" name="extract_day" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="extract_month">Month</label>
					<input value="<?=$month?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="extract_month" maxlength="2" name="extract_month" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="extract_year">Year</label>
					<input value="<?=$year?>" class="govuk-input govuk-date-input__input govuk-input--width-4" id="extract_year" maxlength="4" name="extract_year" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="extract_hour">Hour</label>
					<input value="<?=$hour?>" class="govuk-input govuk-date-input__input govuk-input--width-4" id="extract_hour" maxlength="4" name="extract_hour" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="extract_minute">Minute</label>
					<input value="<?=$minute?>" class="govuk-input govuk-date-input__input govuk-input--width-4" id="extract_minute" maxlength="4" name="extract_minute" type="text" pattern="[0-9]*">
				</div>
			</div>
		</div>
	</fieldset>

</div>
<!-- End validity start date fields //-->
<button type="submit" class="govuk-button">Update date</button>

</form>

</div>

<?php
    require ("includes/footer.php")
?>