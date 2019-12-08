<?php
    $title = "Create or edit regulation";
	require ("includes/db.php");
	$application = new application;
	$application->get_regulation_groups();
	$error_handler = new error_handler;

    # Initialise the quota order number object
	$action				= get_querystring("action");
	$base_regulation_id = get_querystring("base_regulation_id");
	$base_regulation	= new base_regulation;
	$base_regulation->set_properties($base_regulation_id);

	# Initialise the quota order number origin object

	$disabled = "";
	switch ($action) {
        case "new":
            $base_regulation->populate_from_cookies();
			$disabled = "";
            $phase = "regulation_create";
			break;
		case "edit":
			$base_regulation->base_regulation_id    = get_querystring("base_regulation_id");
			$base_regulation->populate_from_db();
            $disabled = " disabled";
            $phase = "regulation_edit";
			break;
	}

    require ("includes/header.php");
?>
<!-- Start breadcrumbs //-->
<div id="wrapper" class="direction-ltr">
	<div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
	<ol class="govuk-breadcrumbs__list">
		<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/">Main menu</a></li>
		<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/regulations.html">Goods classification</a></li>
		<li class="govuk-breadcrumbs__list-item">0102030405</li>
	</ol>
</div>
<!-- End breadcrumbs //-->

<div class="app-content__header">
	<h1 class="govuk-heading-xl" style="margin:1em 0px 0px 0px">Edit commodity code dates</h1>
</div>
<div class="govuk-inset-text">
	0102030405 - Lorem ipsum dolor sit amet, consectetur adipiscing elit
</div>

<div style="width:80%">
<details class="govuk-details" data-module="govuk-details">
  <summary class="govuk-details__summary">
    <span class="govuk-details__summary-text">Workbasket details</span>
  </summary>
  <div class="govuk-details__text">
    <table class="govuk-table" cellspacing="0" xstyle="width:70%">
        <tr class="govuk-table__row" valign="top" style="vertical-align:top !important">
            <th class="govuk-table__header" style="width:20%" style="vertical-align:top !important">Workbasket name</th>
            <td class="govuk-table__cell" style="width:80%" style="vertical-align:top !important">Lorem isum dolor sit amet</td>
        </tr>
        <tr class="govuk-table__row" valign="top">
            <th class="govuk-table__header" style="vertical-align:top !important">Reason for change</th>
            <td class="govuk-table__cell" style="vertical-align:top !important">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi ullamcorper lacus eu auctor iaculis. Sed et aliquet turpis. Nullam ac mauris metus. In at nibh vel enim iaculis faucibus.</td>
        </tr>
    </table>
  </div>
</details>

<details class="govuk-details" data-module="govuk-details">
  <summary class="govuk-details__summary">
    <span class="govuk-details__summary-text">
      Commodity code details
    </span>
  </summary>
  <div class="govuk-details__text">
    <table class="govuk-table" cellspacing="0" xstyle="width:70%">
        <tr class="govuk-table__row" valign="top" style="vertical-align:top !important">
            <th class="govuk-table__header" style="width:20%" style="vertical-align:top !important">Commmodity code</th>
            <td class="govuk-table__cell" style="width:80%" style="vertical-align:top !important">0102030405</td>
        </tr>
        <tr class="govuk-table__row" valign="top">
            <th class="govuk-table__header" style="vertical-align:top !important">Product line suffix</th>
            <td class="govuk-table__cell" style="vertical-align:top !important">80</td>
        </tr>
        <tr class="govuk-table__row" valign="top">
            <th class="govuk-table__header" style="vertical-align:top !important">Description</th>
            <td class="govuk-table__cell" style="vertical-align:top !important">Lorem ipsum dolor sit amet, consectetur adipiscing elit</td>
        </tr>
    </table>
  </div>
</details>

<details class="govuk-details" data-module="govuk-details">
  <summary class="govuk-details__summary">
    <span class="govuk-details__summary-text">
      Associated measures (3)
    </span>
  </summary>
  <div class="govuk-details__text">
    <p>There are no active measures assigned to this commodity code.</p>

    <p>There are 3 active measures assigned to this commodity code. If you choose to terminate this
		commodity code, then any unterminated measures (or measures that
		end after your selected end date) 
		will also be terminated on the same date.
    </p>
    <table class="govuk-table" cellspacing="0" style="xwidth:70%">
        <tr class="govuk-table__row" valign="top" style="vertical-align:top !important">
            <th class="govuk-table__header" style="width:35%">Measure type</th>
            <th class="govuk-table__header" style="width:35%">Geographical area</th>
            <th class="govuk-table__header" style="width:15%">Start date</th>
            <th class="govuk-table__header" style="width:15%">End date</th>
        </tr>
        <tr class="govuk-table__row" valign="top">
            <td class="govuk-table__cell">142 (Tariff preference)</td>
            <td class="govuk-table__cell">CL (Chile)</td>
            <td class="govuk-table__cell">01 Sep 2012</td>
            <td class="govuk-table__cell">-</td>
        </tr>
        <tr class="govuk-table__row" valign="top">
            <td class="govuk-table__cell">142 (Tariff preference)</td>
            <td class="govuk-table__cell">QZ (Countries and territories not specified for commercial or military reasons in the framework of trade with third countries)</td>
            <td class="govuk-table__cell">01 Sep 2012</td>
            <td class="govuk-table__cell">-</td>
        </tr>
        <tr class="govuk-table__row" valign="top">
            <td class="govuk-table__cell">771 (Import control of timber and timber products subject to the FLEGT licensing scheme)</td>
            <td class="govuk-table__cell">1011 (Erga Omnes)</td>
            <td class="govuk-table__cell">01 Sep 2012</td>
            <td class="govuk-table__cell">-</td>
        </tr>
    </table>
  </div>
</details>


<details class="govuk-details" data-module="govuk-details">
	<summary class="govuk-details__summary">
    <span class="govuk-details__summary-text">Associated footnotes (1)</span>
  </summary>
  <div class="govuk-details__text">
    <p>There are no active footnotes associated with this commodity code.</p>

    <p>There is 1 active footnote associated with this commodity code. If you choose to terminate this
		commodity code, then any unterminated footnote associations (or footnote associations that
		end after your selected end date) will also be terminated on the same date.
    </p>
    <table class="govuk-table" cellspacing="0" style="xwidth:70%">
        <tr class="govuk-table__row" valign="top" style="vertical-align:top !important">
            <th class="govuk-table__header" style="width:10%">Code</th>
            <th class="govuk-table__header" style="width:50%">Description</th>
            <th class="govuk-table__header" style="width:20%">Start date</th>
            <th class="govuk-table__header" style="width:20%">End date</th>
        </tr>
        <tr class="govuk-table__row" valign="top">
            <td class="govuk-table__cell">TN084</td>
            <td class="govuk-table__cell">Products consigned from Japan shall be accompanied by a Common Entry Document (CED)
				or a Common Veterinary Entry Document (CVED) according to Commission Implementing Regulation (EU) 2016/6 (OJ L 3).</td>
            <td class="govuk-table__cell">01 September 2012</td>
            <td class="govuk-table__cell">-</td>
        </tr>
    </table>
  </div>
</details>

<form class="tariff" method="post" action="/actions/regulation_actions.html">
<!-- Start error handler //-->
<?=$error_handler->get_primary_error_block() ?>
<!-- End error handler //-->
<!-- Begin validity start date fields //-->
<div class="govuk-form-group <?=$error_handler->get_error("validity_start_date");?>" style="margin-top:2em">
	<fieldset class="govuk-fieldset" aria-describedby="validity_start_hint" role="group">
		<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
			<h1 id="heading_validity_start_date" class="govuk-fieldset__heading" style="max-width:100%;">Start date</h1>
		</legend>
		<span id="more-detail-hint" class="govuk-hint">
	If you choose to move this commodity code's start date forward in time and there are measures or footnotes
	associated with the commodity code, then you are likely to get errors n shit.
  </span>
  		<?=$error_handler->display_error_message("validity_start_date");?>
		<div class="govuk-date-input" id="validity_start">
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_start_day">Day</label>
					<input value="01" class="govuk-input govuk-date-input__input govuk-input--width-2" id="validity_start_day" maxlength="2" name="validity_start_day" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_start_month">Month</label>
					<input value="01" class="govuk-input govuk-date-input__input govuk-input--width-2" id="validity_start_month" maxlength="2" name="validity_start_month" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_start_year">Year</label>
					<input value="2010" class="govuk-input govuk-date-input__input govuk-input--width-4" id="validity_start_year" maxlength="4" name="validity_start_year" type="text" pattern="[0-9]*">
				</div>
			</div>
		</div>
	</fieldset>
</div>
<!-- End validity start date fields //-->


<!-- Begin validity start date fields //-->
<div class="govuk-form-group <?=$error_handler->get_error("validity_start_date");?>">
	<fieldset class="govuk-fieldset" aria-describedby="validity_start_hint" role="group">
		<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
			<h1 id="heading_validity_start_date" class="govuk-fieldset__heading" style="max-width:100%;">End date</h1>
		</legend>
  <span id="more-detail-hint" class="govuk-hint">
	If you choose to terminate this commodity code, then any unterminated or measures and footnote associations will also be terminated on the same date.
	Any measures or footnote measures that start after the selected end date will be deleted.
  </span>
  <?=$error_handler->display_error_message("validity_start_date");?>
		<div class="govuk-date-input" id="validity_start">
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_start_day">Day</label>
					<input value="" class="govuk-input govuk-date-input__input govuk-input--width-2" id="validity_start_day" maxlength="2" name="validity_start_day" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_start_month">Month</label>
					<input value="" class="govuk-input govuk-date-input__input govuk-input--width-2" id="validity_start_month" maxlength="2" name="validity_start_month" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_start_year">Year</label>
					<input value="" class="govuk-input govuk-date-input__input govuk-input--width-4" id="validity_start_year" maxlength="4" name="validity_start_year" type="text" pattern="[0-9]*">
				</div>
			</div>
		</div>
	</fieldset>
</div>
<!-- End validity start date fields //-->


        <button type="submit" class="govuk-button">Continue</button>
        <a onclick="history.back()" class="secondary-button" href="#">Cancel</a>
	</form>
</div>
</div>
<?php
	require ("includes/footer.php")
?>