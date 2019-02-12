<?php
    require ("includes/header.php");
?>
            <div class="app-content__header">
                <h1 class="govuk-heading-xl">Main menu</h1>
            </div>
            <div class="app-prose-scope">
                <div class="column-one-third">
                    <h2 class="small">Measures</h2>
                    <ul class="main-menu">
                        <li><a href="/measures.php">Measures</a></li>
                        <!--<li><a href="/measure_create.php">Create measure</a></li>//-->
                    </ul>

                    <h2 class="small">Quotas</h2>
                    <ul class="main-menu">
                        <li><a href="/quota_order_numbers.php">Quota order numbers</a></li>
                        <!--<li><a href="/quota_order_numbers.php">Create quota</a></li>//-->
                    </ul>

                    <h2 class="small">Regulations</h2>
                    <ul class="main-menu">
                        <li><a href="/regulations.php">Regulations</a></li>
                        <li><a href="/regulation_groups.php">Regulation groups</a></li>
                        <li><a href="/regulation_create.php">Create regulation</a></li>
                    </ul>

                    <h2 class="small">Geographical areas</h2>
                    <ul class="main-menu">
                        <li><a href="/geographical_areas.php">Geographical areas</a></li>
                    </ul>
                </div>


                <div class="column-one-third">
                    <h2 class="small">Measure types</h2>
                    <ul class="main-menu">
                        <li><a href="/measure_types.php">Measure types</a></li>
                        <li><a href="/measure_type_create.php">Create measure type</a></li>
                    </ul>

                    <h2 class="small">Measure type series</h2>
                    <ul class="main-menu">
                        <li><a href="/measure_type_series.php">Measure type series</a></li>
                    </ul>

                    <h2 class="small">Footnotes</h2>
                    <ul class="main-menu">
                        <li><a href="/footnotes.php">Footnotes</a></li>
                        <li><a href="/footnote_types.php">Footnote types</a></li>
                    </ul>

                    <h2 class="small">Certificates</h2>
                    <ul class="main-menu">
                        <li><a href="/certificates.php">Certificates</a></li>
                        <li><a href="/certificate_types.php">Certificate types</a></li>
                    </ul>



                </div>


                <div class="column-one-third">
                    <h2 class="small">Nomenclature</h2>
                    <ul class="main-menu">
                        <li><a href="/sections.php">View sections</a></li>
                    </ul>

                    <h2 class="small">Additional codes</h2>
                    <ul class="main-menu">
                        <li><a href="/additional_codes.php">Additional codes</a></li>
                        <li><a href="/additional_code_types.php">Additional code types</a></li>
                    </ul>

                    <h2 class="small">Load history</h2>
                    <ul class="main-menu">
                        <li><a href="/load_history.php">Load history</a></li>
                    </ul>

                    <h2 class="small">Monetary exchange rates</h2>
                    <ul class="main-menu">
                    <li><a href="/monetary_exchange_rates.php">Monetary exchange rates</a></li>
                    <li><a href="/monetary_exchange_rate_create.php">Create new monetary exchange rate</a></li>
                    </ul>


                </div>
            <div class="clearer"><!--&nbsp;//--></div>
        </div>
</div>

<?php
    require ("includes/footer.php")
?>