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
		<li class="govuk-breadcrumbs__list-item">Add new commodity code</li>
	</ol>
</div>
<!-- End breadcrumbs //-->

<div class="app-content__header">
	<h1 class="govuk-heading-xl" style="margin:1em 0px 0px 0px">Add new commodity code</h1>
</div>
<div class="govuk-inset-text">
	Parent code <strong>0102030405</strong> - Lorem ipsum dolor sit amet, consectetur adipiscing elit
</div>

<div style="width:80%">
<details class="govuk-details" data-module="govuk-details">
  <summary class="govuk-details__summary">
	<span class="govuk-details__summary-text">Workbasket details</span>
  </summary>
  <div class="govuk-details__text">
	<table class="govuk-table" cellspacing="0" xstyle="width:70%">
		<tr class="govuk-table__row" valign="top" style="vertical-align:top !important">
			<th class="govuk-table__header nopad" style="width:20%" style="vertical-align:top !important">Workbasket name</th>
			<td class="govuk-table__cell" style="width:80%" style="vertical-align:top !important">Lorem isum dolor sit amet</td>
		</tr>
		<tr class="govuk-table__row" valign="top">
			<th class="govuk-table__header nopad" style="vertical-align:top !important">Reason for change</th>
			<td class="govuk-table__cell" style="vertical-align:top !important">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi ullamcorper lacus eu auctor iaculis. Sed et aliquet turpis. Nullam ac mauris metus. In at nibh vel enim iaculis faucibus.</td>
		</tr>
	</table>
  </div>
</details>

<details class="govuk-details" data-module="govuk-details">
  <summary class="govuk-details__summary">
	<span class="govuk-details__summary-text">
	  Parent code details
	</span>
  </summary>
  <div class="govuk-details__text">
	<table class="govuk-table" cellspacing="0" xstyle="width:70%">
		<tr class="govuk-table__row" valign="top" style="vertical-align:top !important">
			<th class="govuk-table__header nopad" style="width:20%" style="vertical-align:top !important">Commmodity code</th>
			<td class="govuk-table__cell" style="width:80%" style="vertical-align:top !important">0102030405</td>
		</tr>
		<tr class="govuk-table__row" valign="top">
			<th class="govuk-table__header nopad" style="vertical-align:top !important">Product line suffix</th>
			<td class="govuk-table__cell" style="vertical-align:top !important">80</td>
		</tr>
		<tr class="govuk-table__row" valign="top">
			<th class="govuk-table__header nopad" style="vertical-align:top !important">Description</th>
			<td class="govuk-table__cell" style="vertical-align:top !important">Lorem ipsum dolor sit amet, consectetur adipiscing elit</td>
		</tr>
	</table>
  </div>
</details>



<form class="tariff" method="post" action="/actions/regulation_actions.html">
<!-- Start error handler //-->
<?=$error_handler->get_primary_error_block() ?>
<!-- End error handler //-->



<fieldset class="govuk-fieldset" style="margin-top:2em">
  <legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
	<h1 class="govuk-fieldset__heading">Define the new commodity code</h1>
  </legend>

  <div class="govuk-form-group">
	<label class="govuk-label" for="goods_nomenclature_item_id">New code</label>
	<span id="more-detail-hint" class="govuk-hint">
		Please enter a 10-digit commodity code. Be sure to include leading zeroes if required.
	  </span>
	<input class="govuk-input" id="goods_nomenclature_item_id" pattern="[0-9]{10}" maxlength="10" size="10" name="goods_nomenclature_item_id" type="text" style="width:10em">
  </div>

	<div class="govuk-form-group">
		<label class="govuk-label" for="productline_suffix">Product line suffix</label>
		<span id="more-detail-hint" class="govuk-hint">
			Please use a suffix of &quot;80&quot; for all end-line codes. Other codes, such as &quot;10&quot;, &quot;20&quot; or &quot;30&quot; may be used for intermediate lines.
		</span>
		<input class="govuk-input" size="2" maxlength="2" pattern="[0-9]{2}" id="productline_suffix" name="productline_suffix" type="text" style="width:4em">
	</div>

	<div class="govuk-form-group">
		<label class="govuk-label" for="number_indents">Number of indents</label>
		<span id="more-detail-hint" class="govuk-hint">
			This is pre-populated with an indent value one greater than the existing parent: this may be overwritten, however
			any change to this code may lead to future issues managing the goods nomenclature.
		</span>
		<input class="govuk-input" value="2" size="2" maxlength="2" pattern="[0-9]{1,2}" id="number_indents" name="number_indents" type="text" style="width:4em">
	</div>



<div class="govuk-character-count" data-module="govuk-character-count" data-maxlength="3000">
  <div class="govuk-form-group">
    <label class="govuk-label" for="with-hint">Commodity description</label>
    <textarea class="govuk-textarea govuk-js-character-count" id="with-hint" name="with-hint" rows="3" aria-describedby="with-hint-info with-hint-hint"></textarea>
  </div>

  <span id="with-hint-info" class="govuk-hint govuk-character-count__message" aria-live="polite">
    You can enter up to 3000 characters
  </span>
  <details class="govuk-details" data-module="govuk-details">
		<summary class="govuk-details__summary">
			<span class="govuk-details__summary-text">Help on formatting descriptions</span>
		</summary>
		<div class="govuk-details__text">
			<span id="more-detail-hint" class="govuk-hint">
				There are a number of conventions used in defining commodity code descriptions, as follows:<br /><br />
				<table class="govuk-table" cellspacing="0" style="margin:0px">
					<tr class="govuk-table__row" valign="top" style="vertical-align:top !important">
						<th class="govuk-table__header nopad" style="width:20%">Code</th>
						<th class="govuk-table__header" style="width:80%">Description</th>
					</tr>
					<tr style="vertical-align:top !important">
						<td class="govuk-table__cell nopad">&lt;sup&gt;lipsum&lt;/sup&gt;</td>
						<td class="govuk-table__cell">
							Surround a piece of text in a &lt;sup&gt;&lt;/sup&gt; tag pair to show it in superscript. For example:<br /><br />
							Of glass having a linear coefficient of expansion not exceeding 5 x 10<sup>-6</sup> per Kelvin<br />
							<em>5 x 10&lt;sup&gt;-6&lt;/sup&gt; per Kelvin</em></td>
					</tr>
					<tr style="vertical-align:top !important">
						<td class="govuk-table__cell nopad">&lt;sub&gt;lipsum&lt;/sub&gt;</td>
						<td class="govuk-table__cell">
							Surround a piece of text in a &lt;sub&gt;&lt;/sub&gt; tag pair to show it in subscript. For example:<br /><br />
							Containing, by weight, 93 % or more of silica (SiO<sub>2</sub>)<br />
							<em>silica (SiO&lt;sub&gt;2&lt;/sub&gt;)</em></td>
					</tr>
					<tr style="vertical-align:top !important">
						<td class="govuk-table__cell nopad">!o!</td>
						<td class="govuk-table__cell">Degree symbol<br /><br />
							With a degree of concentration higher than 20&deg; brix<br />
							<em>With a degree of concentration higher than 20!o! brix</em>
						</td>
					</tr>
				</table>
			</span>
		</div>
	</details>  
</div>

  
</fieldset>



<!-- Begin validity start date fields //-->
<div class="govuk-form-group <?=$error_handler->get_error("validity_start_date");?>">
	<fieldset class="govuk-fieldset" aria-describedby="validity_start_hint" role="group">
		<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
			<h1 id="heading_validity_start_date" class="govuk-fieldset__heading" style="max-width:100%;">When will the new commodity code come into force?</h1>
		</legend>
		<span id="more-detail-hint" class="govuk-hint">
			Please select the date on which the new commodity code is due to come into force. This can be changed at a later date if required.
  		</span>
  		<?=$error_handler->display_error_message("validity_start_date");?>
		<div class="govuk-date-input" id="validity_start">
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_start_day">Day</label>
					<input xvalue="01" class="govuk-input govuk-date-input__input govuk-input--width-2" id="validity_start_day" maxlength="2" name="validity_start_day" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_start_month">Month</label>
					<input xvalue="01" class="govuk-input govuk-date-input__input govuk-input--width-2" id="validity_start_month" maxlength="2" name="validity_start_month" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_start_year">Year</label>
					<input xvalue="2010" class="govuk-input govuk-date-input__input govuk-input--width-4" id="validity_start_year" maxlength="4" name="validity_start_year" type="text" pattern="[0-9]*">
				</div>
			</div>
		</div>
	</fieldset>
</div>
<!-- End validity start date fields //-->


<fieldset class="govuk-fieldset" style="margin-top:2em">
  <legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
	<h1 style="max-width:100%" class="govuk-fieldset__heading">Define the origin for the new commodity code</h1>
  </legend>
  <p style="margin-top:0px;">The origin is used to provide an audit trail. By default, the parent's code &amp; suffix
  are selected for the origin.</p>

  <div class="govuk-form-group">
	<label class="govuk-label" for="goods_nomenclature_item_id">Origin code</label>
	<input class="govuk-input" value="0102030405" id="goods_nomenclature_item_id" maxlength="10" size="10" pattern="[0-9]{10}" name="goods_nomenclature_item_id" type="text" style="width:10em">
  </div>

  <div class="govuk-form-group">
	<label class="govuk-label" for="productline_suffix">Product line suffix</label>
	<input class="govuk-input" value="80" size="2" maxlength="2" pattern="[0-9]{1,2}" id="productline_suffix" name="productline_suffix" type="text" style="width:4em">
  </div>

</details>  
</div>

  
</fieldset>



		<button type="submit" class="govuk-button">Continue</button>
		<a onclick="history.back()" class="secondary-button" href="#">Cancel</a>
	</form>
</div>
</div>
<?php
	require ("includes/footer.php")
?>