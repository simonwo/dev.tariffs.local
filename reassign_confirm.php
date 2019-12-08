<?php
    $title = "Delete measure";
    require("includes/db.php");
    require("includes/header.php");
    $measure_sid                = get_querystring("measure_sid");
    $goods_nomenclature_item_id = get_querystring("goods_nomenclature_item_id");
    $geographical_area_id       = get_querystring("geographical_area_id");
?>
<div id="wrapper" class="direction-ltr">
  <!-- Start breadcrumbs //-->
  <div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
    <ol class="govuk-breadcrumbs__list">
      <li class="govuk-breadcrumbs__list-item">
        <a class="govuk-breadcrumbs__link" href="/">Main menu</a>
      </li>
      <li class="govuk-breadcrumbs__list-item">Measures</li>
    </ol>
  </div>
  <!-- End breadcrumbs //-->
  <div class="govuk-box-highlight">
    <h1 class="heading-xlarge" style="xround-color:#f00;max-width:100%">Workbasket reassigned</h1>
    <p class="font-large">The workbasket <strong class='bold'>New requirement for Singapore Trade Agreement</strong> has been assigned to <strong>Marjorie Antrobus</strong>.</p>
  </div>
  <h3 class="heading-medium m-t-100">Next steps</h3>
  <ul class="list">
    <li><a href="https://manage-trade-tariffs.trade.dev.uktrade.io/">Return to main menu</a></li>
  </ul>
</div>

<?php
require("includes/footer.php")
?>