<?php
	$title = "Quota events";
	require ("includes/db.php");
	$quota_definition_sid = get_querystring("quota_definition_sid");
	$quota_order_number_id = get_querystring("quota_order_number_id");
	$measurement_unit_code = get_querystring("measurement_unit_code");
	require ("includes/header.php");
	$key_date = "1919-01-01";
?>

<div id="wrapper" class="direction-ltr">
	<!-- Start breadcrumbs //-->
	<div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
		<ol class="govuk-breadcrumbs__list">
			<li class="govuk-breadcrumbs__list-item">
				<a class="govuk-breadcrumbs__link" href="/">Main menu</a>
			</li>
			<li class="govuk-breadcrumbs__list-item">
				<a class="govuk-breadcrumbs__link" href="quota_order_number_view.html?quota_order_number_id=<?=$quota_order_number_id?>">Quota order number <?=$quota_order_number_id?></a>
			</li>
			<li class="govuk-breadcrumbs__list-item">Quota events</li>
		</ol>
	</div>
	<!-- End breadcrumbs //-->
  
	<div class="app-content__header">
		<h1 style="margin-bottom:0px" class="govuk-heading-xl">View quota events</h1>
	</div>
	<p>Showing all events for definition
	<a href="quota_order_number_view.html?quota_order_number_id=<?=$quota_order_number_id?>#def<?=$quota_definition_sid?>"><?=$quota_definition_sid?></a>
	on quota order number
	<a href="quota_order_number_view.html?quota_order_number_id=<?=$quota_order_number_id?>"><?=$quota_order_number_id?></a>
	that have been created.
	</p>
	<ul class="tariff_menu">
		<li><a href="#suspension_periods">Suspension periods</a></li>
		<li><a href="#blocking_periods">Blocking periods</a></li>
		<li><a href="#quota_balance_events">Quota balance events</a></li>
		<li><a href="#quota_critical_events">Quota critical events</a></li>
		<li><a href="#quota_reopening_events">Quota reopening events</a></li>
		<li><a href="#quota_unblocking_events">Quota unblocking events</a></li>
	</ul>



	<h2 id="suspension_periods" class="govuk-heading-m" style="margin-bottom:0px;margin-top:2em">Suspension periods</h2>
<?php
	$sql = "select suspension_start_date, suspension_end_date, description, quota_definition_sid from quota_suspension_periods
	where quota_definition_sid = " . $quota_definition_sid . "
	order by suspension_start_date desc";
	$result = pg_query($conn, $sql);
	if  ($result) {
		$row_count = pg_num_rows($result);
		if ($row_count > 0) {
?>
	<table class="govuk-table" cellspacing="0">
		<tr class="govuk-table__row">
			<th class="govuk-table__header nopad" style="width:10%">Start date</th>
			<th class="govuk-table__header" style="width:10%">End date</th>
			<th class="govuk-table__header" style="width:80%">Description</th>
		</tr>
<?php    
		while ($row = pg_fetch_array($result)) {
			$suspension_start_date	= short_date($row['suspension_start_date']);
			$suspension_end_date   	= short_date($row['suspension_end_date']);
			$description            = $row['description'];
?>
		<tr>
			<td class="govuk-table__cell nopad"><?=$suspension_start_date?></td>
			<td class="govuk-table__cell"><?=$suspension_end_date?></td>
			<td class="govuk-table__cell"><?=$description?></td>
		</tr>
<?php            
		}
?>        
	<table>
<?php            
	} else { echo ("<p>No suspension periods</p>");}
	}
?>
	<p class="back_to_top"><a href="#top">Back to top</a></p>


	<h2 id="blocking_periods" class="govuk-heading-m" style="margin-bottom:0px;margin-top:2em">Blocking periods</h2>
<?php
$sql = "select blocking_start_date, blocking_end_date, description, quota_definition_sid from quota_blocking_periods
where quota_definition_sid = " . $quota_definition_sid . "
order by blocking_start_date desc";
$result = pg_query($conn, $sql);
if  ($result) {
  $row_count = pg_num_rows($result);
  if ($row_count > 0) {
?>
<table class="govuk-table" cellspacing="0">
  <tr class="govuk-table__row">
    <th class="govuk-table__header nopad" style="width:10%">Start date</th>
    <th class="govuk-table__header" style="width:10%">End date</th>
    <th class="govuk-table__header" style="width:80%">Description</th>
  </tr>
<?php    
  while ($row = pg_fetch_array($result)) {
    $blocking_start_date	= short_date($row['blocking_start_date']);
    $blocking_end_date   	= short_date($row['blocking_end_date']);
    $description            = $row['description'];
?>
  <tr>
    <td class="govuk-table__cell nopad"><?=$blocking_start_date?></td>
    <td class="govuk-table__cell"><?=$blocking_end_date?></td>
    <td class="govuk-table__cell"><?=$description?></td>
  </tr>
<?php            
  }
?>        
<table>
<?php            
} else { echo ("<p>No blocking periods</p>");}
}
?>
<p class="back_to_top"><a href="#top">Back to top</a></p>


	<h2 id="quota_balance_events" class="govuk-heading-m" style="margin-bottom:0px;margin-top:2em">Quota balance events</h2>
<?php
	$sql = "select occurrence_timestamp, old_balance, new_balance, imported_amount from quota_balance_events
	where occurrence_timestamp >= '" . $key_date . "'
	and quota_definition_sid = " . $quota_definition_sid . " order by occurrence_timestamp desc";
	$result = pg_query($conn, $sql);
	if  ($result) {
		$row_count = pg_num_rows($result);
		if ($row_count > 0) {
?>
	<table class="govuk-table" cellspacing="0">
		<tr class="govuk-table__row">
			<th class="govuk-table__header nopad">Timestamp</th>
			<th class="govuk-table__header">Old balance (<?=$measurement_unit_code?>)</th>
			<th class="govuk-table__header">New balance (<?=$measurement_unit_code?>)</th>
			<th class="govuk-table__header">Imported amount (<?=$measurement_unit_code?>)</th>
		</tr>
<?php    
		while ($row = pg_fetch_array($result)) {
			$occurrence_timestamp   = short_date($row['occurrence_timestamp']);
			$old_balance            = $row['old_balance'];
			$new_balance            = $row['new_balance'];
			$imported_amount        = $row['imported_amount'];
?>
		<tr>
			<td class="govuk-table__cell nopad"><?=$occurrence_timestamp?></td>
			<td class="govuk-table__cell"><?=number_format($old_balance, 3, '.', ',')?></td>
			<td class="govuk-table__cell"><?=number_format($new_balance, 3, '.', ',')?></td>
			<td class="govuk-table__cell"><?=number_format($imported_amount, 3, '.', ',')?></td>
		</tr>
<?php            
		}
?>        
	<table>
<?php            
	} else { echo ("<p>No balance events</p>");}
	}
?>
	<p class="back_to_top"><a href="#top">Back to top</a></p>



	<h2 id="quota_critical_events" class="govuk-heading-m" style="margin-bottom:0px;margin-top:2em">Quota critical events</h2>
<?php
	$sql = "select occurrence_timestamp, critical_state from quota_critical_events
	where occurrence_timestamp >= '" . $key_date . "'
	and quota_definition_sid = " . $quota_definition_sid . " order by occurrence_timestamp desc";
	$result = pg_query($conn, $sql);
	if  ($result) {
		$row_count = pg_num_rows($result);
		if ($row_count > 0) {
?>
	<table class="govuk-table" cellspacing="0">
		<tr class="govuk-table__row">
			<th class="govuk-table__header nopad">Timestamp</th>
			<th class="govuk-table__header">Critical state</th>
		</tr>
<?php    
		while ($row = pg_fetch_array($result)) {
			$occurrence_timestamp   = short_date($row['occurrence_timestamp']);
			$critical_state            = $row['critical_state'];
?>
		<tr>
			<td class="govuk-table__cell nopad"><?=$occurrence_timestamp?></td>
			<td class="govuk-table__cell"><?=$critical_state?></td>
		</tr>
<?php            
		}
?>        
	<table>
<?php
	} else { echo ("<p>No critical events</p>");}
	}
?>
	<p class="back_to_top"><a href="#top">Back to top</a></p>


	<h2 id="quota_reopening_events" class="govuk-heading-m" style="margin-bottom:0px;margin-top:2em">Quota reopening events</h2>
<?php
	$sql = "select occurrence_timestamp, reopening_date from quota_reopening_events
	where occurrence_timestamp >= '" . $key_date . "'
	and quota_definition_sid = " . $quota_definition_sid . " order by occurrence_timestamp desc";
	$result = pg_query($conn, $sql);
	if  ($result) {
		$row_count = pg_num_rows($result);
		if ($row_count > 0) {
?>
	<table class="govuk-table" cellspacing="0">
		<tr class="govuk-table__row">
			<th class="govuk-table__header nopad">Timestamp</th>
			<th class="govuk-table__header">Reopening date</th>
		</tr>
<?php    
		while ($row = pg_fetch_array($result)) {
			$occurrence_timestamp   = short_date($row['occurrence_timestamp']);
			$reopening_date         = short_date($row['reopening_date']);
?>
		<tr>
			<td class="govuk-table__cell nopad"><?=$occurrence_timestamp?></td>
			<td class="govuk-table__cell"><?=$reopening_date?></td>
		</tr>
<?php            
		}
?>        
	<table>
<?php            
	} else { echo ("<p>No reopening events</p>");}
}

?>
	<p class="back_to_top"><a href="#top">Back to top</a></p>	

	<h2 id="quota_unblocking_events" class="govuk-heading-m" style="margin-bottom:0px;margin-top:2em">Quota unblocking events</h2>
<?php
	$sql = "select occurrence_timestamp, unblocking_date from quota_unblocking_events
	where occurrence_timestamp >= '" . $key_date . "'
	and quota_definition_sid = " . $quota_definition_sid . " order by occurrence_timestamp desc";
	$result = pg_query($conn, $sql);
	if  ($result) {
		$row_count = pg_num_rows($result);
		if ($row_count > 0) {
?>
	<table class="govuk-table" cellspacing="0">
		<tr class="govuk-table__row">
			<th class="govuk-table__header nopad">Timestamp</th>
			<th class="govuk-table__header">Unblocking date</th>
		</tr>
<?php    
		while ($row = pg_fetch_array($result)) {
			$occurrence_timestamp   = short_date($row['occurrence_timestamp']);
			$unblocking_date        = short_date($row['unblocking_date']);
?>
		<tr>
			<td class="govuk-table__cell nopad"><?=$occurrence_timestamp?></td>
			<td class="govuk-table__cell"><?=$old_balance?></td>
			<td class="govuk-table__cell"><?=$new_balance?></td>
		</tr>
<?php            
		}
?>        
	<table>
<?php            
	} else { echo ("<p>No unblocking events</p>");}
}

?>
	<p class="back_to_top"><a href="#top">Back to top</a></p>

</div>
<?php
	require ("includes/footer.php");
?>