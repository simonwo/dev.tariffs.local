<?php
if (isset($_SESSION["user_id"])) {
    if ($_SESSION["user_id"] != null) {
        //if (1 > 2) {
?>
        <!-- Start phase banner //-->
        <div class="govuk-phase-banner">
            <p class="govuk-phase-banner__content">
                <strong class="govuk-tag govuk-phase-banner__content__tag">beta</strong>
                <span class="govuk-phase-banner__text">
                    This is a new service – your <a class="govuk-link" href="#">feedback</a> will help us to improve it.
                </span>
            </p>
        </div>
        <!-- End phase banner //-->
<?php
    }
}
?>
