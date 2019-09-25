<?php
	$title = "Create quota association";
	require ("includes/db.php");
	$application    = new application;
	$phase          = get_querystring("phase");
	$quota_association  = new quota_association;

	if ($phase == "edit") {
		$footnote->footnote_id = $footnote_id;
		$footnote->populate_from_db();
		$phase = "footnote_edit";
	} else {
		$quota_association->populate_from_cookies();
		$phase = "footnote_create2";
	}

	$error_handler = new error_handler;
	require ("includes/header.php");
?>
<div id="wrapper" class="direction-ltr">
<!-- Start breadcrumbs //-->
	<div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
		<ol class="govuk-breadcrumbs__list">
			<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/">Main menu</a></li>
			<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/quota_order_numbers.html">Quota associations</a></li>
			<li class="govuk-breadcrumbs__list-item">Cross-check and edit quota association</li>
		</ol>
	</div>
<!-- End breadcrumbs //-->

<h1 class="heading-large">Cross-check and edit quota association</h1>
<div class="view-workbasket-container">
	<div class="alert create-measures-message-block alert--info">
		<svg class="icon icon--info" version="1.1" viewbox="0 0 24 28" xmlns="http://www.w3.org/2000/svg">
			<path d="M16 21.5v-2.5c0-0.281-0.219-0.5-0.5-0.5h-1.5v-8c0-0.281-0.219-0.5-0.5-0.5h-5c-0.281 0-0.5 0.219-0.5 0.5v2.5c0 0.281 0.219 0.5 0.5 0.5h1.5v5h-1.5c-0.281 0-0.5 0.219-0.5 0.5v2.5c0 0.281 0.219 0.5 0.5 0.5h7c0.281 0 0.5-0.219 0.5-0.5zM14 7.5v-2.5c0-0.281-0.219-0.5-0.5-0.5h-3c-0.281 0-0.5 0.219-0.5 0.5v2.5c0 0.281 0.219 0.5 0.5 0.5h3c0.281 0 0.5-0.219 0.5-0.5zM24 14c0 6.625-5.375 12-12 12s-12-5.375-12-12 5.375-12 12-12 12 5.375 12 12z"></path>
		</svg>
		<p>Please check that the amendments below are as intended and correctly reflect the requirement, then either confirm or reject.</p>
</div>
<h3 class="heading-medium">Workbasket details</h3>
<table class="create-measures-details-table">
	<tbody>
		<tr>
			<td class="heading_column">Created by</td>
			<td>Name of creator</td>
		</tr>
		<tr>
			<td class="heading_column">Created on</td>
			<td>Date of creation</td>
		</tr>
		<tr>
			<td class="heading_column">Submitted for cross-check on</td>
			<td>Date of submission for cross-check</td>
		</tr>
	</tbody>
</table>
<h3 class="heading-medium">Summary of quota association configuration</h3>
<table class="create-measures-details-table">
	<tbody>
		<tr>
			<td class="heading_column">Parent (main) quota order number ID</td>
			<td>091234</td>
		</tr>
		<tr>
			<td class="heading_column">Child (sub) quota order number ID</td>
			<td>096789</td>
		</tr>
		<tr>
			<td class="heading_column">Parent (main) definition period</td>
			<td>01/01/2019 to 31/12/2019</td>
		</tr>
		<tr>
			<td class="heading_column">Child (sub) definition period</td>
			<td>01/01/2019 to 31/12/2019</td>
		</tr>
		<tr>
			<td class="heading_column">Relation type</td>
			<td>EQ</td>
		</tr>
		<tr>
			<td class="heading_column">Coefficient</td>
			<td>1.43567</td>
		</tr>
	</tbody>
</table>
</div>
<form class="simple_form cross-check-form create-measures-v2" id="new_workbasket_forms_workflow_form" action="#" accept-charset="UTF-8" method="post">
	<div class="cross-check-form-fields">
		<h3 class="heading-medium">Confirm cross-check</h3>
		<form-group>
			<label class="form-label"></label>
			<div class="bootstrap-row">
				<div class="controls">
					<div class="cross-check-decision">
						<div class="multiple-choice">
							<input class="radio-inline-group js-cross-check-decision" id="radioID" name="cross_check[mode]" required="true" type="radio" value="approve">
								<label class="with_bigger_font-size" for="radioID">I confirm that I have checked the above details and am satisfied that they are correct.<span class="form-hint with_bigger_font-size">There will be a further approval step before they are sent to CDS.</span></label>
							</input>
						</div>
					</div>
					<div class="cross-check-decision">
						<div class="multiple-choice">
							<input class="radio-inline-group js-cross-check-decision" id="radioID2" name="cross_check[mode]" required="true" type="radio" value="reject">
								<label class="with_bigger_font-size" for="radioID2">I am not satisfied with the above details.</label>
							</input>
						</div>
						<div class="panel panel-border-narrow xhidden js-cross-check-reject-details-block" id="cross-check-rejection-reason">
							<label class="form-label" for="rejection_reason">Provide your reasons and/or state the changes required:</label>
							<div class="parent-group-target col-xs-8 col-md-10 col-lg-12">
								<textarea class="form-control" id="rejection_reason" name="cross_check[reject_reasons]" oninput="setCustomValidity(&#39;&#39;)" oninvalid="this.setCustomValidity(&#39;Please fill in this field.&#39;)" rows="4"></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form-group>
	</div>
	<div class="form-actions">
		<h3 class="heading-medium">Next step</h3>
		<div class="submit_group_for_cross_check_block">
			<input type="submit" name="submit_for_cross_check" value="Finish cross-check" class="btn button" data-disable-with="Finish cross-check" />
		</div>
		<a class="secondary-button" href="https://manage-trade-tariffs.trade.dev.uktrade.io/">Exit (return to main menu)</a></div></form></div></header>
	</div>




</div>

<?php
	require ("includes/footer.php")
?>