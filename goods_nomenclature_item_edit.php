<?php
    $title = "Create or edit regulation";
	require ("includes/db.php");
	$error_handler = new error_handler;

    # Initialise the quota order number object
	$phase				= get_querystring("phase");

	# Initialise the quota order number origin object
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
	<h1 class="govuk-heading-xl">Work with selected commodity code</h1>
</div>

<form class="tariff" method="get" action="/actions/goods_nomenclature_actions.html">
<input name="phase" id="phase" value="goods_nomenclature_edit" type="hidden" />
<!-- Start error handler //-->
<?=$error_handler->get_primary_error_block() ?>
<!-- End error handler //-->


<div class="govuk-inset-text">
  0102030405 - Live horses, asses, mules and hinnies
</div>


<!-- Begin workbasket field //-->
<div class="govuk-form-group">
  <label class="govuk-label" for="workbasket">What is the name of this workbasket?</label>
  <input class="govuk-input" id="workbasket" name="workbasket" type="text" style="width:70%">
</div>
<!-- End workbasket field //-->


<!-- Begin reason field //-->
<div class="govuk-form-group">
  <label class="govuk-label" for="reason">What is the reason for this change?</label>
  <!--<span id="more-detail-hint" class="govuk-hint">
    Do not include personal or financial information, like your National Insurance number or credit card details.
  </span>//-->
  <textarea class="govuk-textarea" id="reason" name="reason" rows="3" aria-describedby="more-detail-hint" style="width:70%"></textarea>
</div>
<!-- End reason field //-->


<div class="govuk-form-group">
  <fieldset class="govuk-fieldset">
    <legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
      <h1 class="govuk-fieldset__heading" style="max-width:100%">
        What do you want to do with this commodity code?
      </h1>
  </legend>
  <span id="more-detail-hint" class="govuk-hint" style="max-width:100%">
    <ul style="margin-top:0px;padding-left:1em !important;margin-left:0px !important;max-width:100%">
        <li>Use the &quot;Modify start / end dates&quot; option if you would like to terminate the code or modify its start date.</li>
        <li>You will not be able to terminate this code if there are any unended child commodity codes.</li>
        <li>Please use the &quot;Delete this code&quot; option if you would like to permanently delete a code created in error.</li>
    </ul>
  </span>
    <div class="govuk-radios">
      <div class="govuk-radios__item">
        <input class="govuk-radios__input" id="where-do-you-live" name="action" type="radio" value="descriptions">
        <label class="govuk-label govuk-radios__label" for="where-do-you-live">Amend description
        </label>
      </div>
      <div class="govuk-radios__item">
        <input class="govuk-radios__input" id="where-do-you-live-2" name="action" type="radio" value="add">
        <label class="govuk-label govuk-radios__label" for="where-do-you-live-2">Add new commodity code</label>
      </div>
      <div class="govuk-radios__item">
        <input class="govuk-radios__input" id="where-do-you-live-4" name="action" type="radio" value="dates">
        <label class="govuk-label govuk-radios__label" for="where-do-you-live-4">Edit commodity code start / end dates</label>
      </div>
      <div class="govuk-radios__item">
        <input class="govuk-radios__input" id="where-do-you-live-3" name="action" type="radio" value="delete">
        <label class="govuk-label govuk-radios__label" for="where-do-you-live-3">Delete this commodity code</label>
      </div>
    </div>
  </fieldset>
</div>
		<button type="submit" class="govuk-button">Continue</button>
		<a onclick="history.back()" class="secondary-button" href="#">Cancel</a>
	</form>
</div>

<?php
	require ("includes/footer.php")
?>