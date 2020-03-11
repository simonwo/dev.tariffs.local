<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("goods_nomenclatures");
?>
<!DOCTYPE html>
<html lang="en" class="govuk-template">
<?php
require("../includes/metadata.php");
?>
<!--Load Bootstrap-->
<link href="/hiren/lib/bootstrap-4.3.1-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="/hiren/lib/bootstrap-4.3.1-dist/css/glyphicons.css" rel="stylesheet" type="text/css" />
<script src="/hiren/lib/bootstrap-4.3.1-dist/js/bootstrap.min.js" type="text/javascript"></script>


<!--Load dataTables-->
<!--
<script src="/hiren/lib/data_table_min.js" type="text/javascript"></script>
//-->
<script src="/hiren/lib/datatables.js" type="text/javascript"></script>

<!--NOTE: use datatablemin.css else data table icons will not work!-->

<link href="/hiren/lib/data_tables/DataTables-1.10.18/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />

<link href="/hiren/lib/data_tables/searchHighlight/dataTables.searchHighlight.css" rel="stylesheet" type="text/css" />
<script src="/hiren/lib/data_tables/searchHighlight/dataTables.searchHighlight.min.js" type="text/javascript"></script>
<script src="/hiren/lib/data_tables/searchHighlight/jquery_highlight_plugin.js" type="text/javascript"></script>


<script src="/hiren/lib/data_tables/export/buttons.html5.min.js" type="text/javascript"></script>
<script src="/hiren/lib/data_tables/export/buttons.print.min.js" type="text/javascript"></script>
<script src="/hiren/lib/data_tables/export/dataTables.buttons.min.js" type="text/javascript"></script>
<script src="/hiren/lib/data_tables/export/jszip.min.js" type="text/javascript"></script>
<script src="/hiren/lib/data_tables/export/vfs_fonts.js" type="text/javascript"></script>


<body class="govuk-template__body">
    <?php
    require("../includes/header.php");
    ?>
    <div class="govuk-width-container">
        <?php
        require("../includes/phase_banner.php");
        ?>


        <main class="govuk-main-wrapper" id="main-content" role="main">

            <div class="govuk-grid-row">
                <div class="govuk-grid-column-full">
                    <h1 class="govuk-heading-xl l">The UK Global Tariff</h1>
                    <?php
                    new inset_control("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Si longus, levis dictata sunt. Qui-vere falsone, quaerere mittimus-dicitur oculis se privasse; Quia nec honesto quic quam honestius nec turpi turpius. Si id dicis, vicimus. Et non ex maxima parte de tota iudicabis? Duo Reges: constructio interrete.<br /><br />

                    An haec ab eo non dicantur? Quid iudicant sensus? Illi enim inter se dissentiunt. Summum ením bonum exposuit vacuitatem doloris; Sed quot homines, tot sententiae; Quam tu ponis in verbis, ego positam in re putabam.");
                    ?>
                    <div id="mytable" class="row container-fluid">
                        <table class="table table-hover govuk-table sticky" id="alltable">
                            <thead class="govuk-table__head">
                                <tr class="govuk-table__row">
                                    <th class="nw govuk-table__header" style="width:10%">Commodity</th>
                                    <th class="nw govuk-table__header" style="width:50%">Description</th>
                                    <th class="nw govuk-table__header" style="width:10%">Common External Tariff</th>
                                    <th class="nw govuk-table__header" style="width:10%">UK Global Tariff</th>
                                    <th class="nw govuk-table__header r" style="width:10%">Change</th>
                                </tr>
                            </thead>

                            <tbody class="ds_table govuk-table__body">

                            </tbody>
                        </table>
                    </div>
                </div>

                <script>
                    $('#alltable').DataTable({
                        "pageLength": 25,
                        "orderable": false,
                        "autoWidth": true,
                        "searchHighlight": true,
                        "ajax": "/gt/data/data.json",
                        "deferRender": true,
                        dom: 'lfrtipB',
                        buttons: [{
                                extend: 'csv',
                                title: "Tariff table".downloadFileName
                            },
                            {
                                extend: 'excel',
                                title: "Tariff table".downloadFileName
                            }
                        ],
                        "searchCols": [
                            {
                                "regex": true
                            },
                            null,
                            null,
                            null,
                            null
                        ],
                        "columns": [{
                                "data": "commodity",
                                "className": "govuk-table__cell",
                                "orderable": false,
                                "render": function(data, type, row, meta) {
                                    s2 = "<span class='rpad mauve'>" + data.substr(0, 4) + "</span><span class='rpad blue'>" + data.substr(4, 2) + "</span><span class='rpad blue'>" + data.substr(6, 2) + "</span>";
                                    return (s2);
                                }
                            },
                            {
                                "data": "description",
                                "className": "govuk-table__cell",
                                "orderable": false
                            },
                            {
                                "data": "cet_duty_rate",
                                "className": "govuk-table__cell nw",
                                "orderable": false
                            },
                            {
                                "data": "ukgt_duty_rate",
                                "className": "govuk-table__cell nw",
                                "orderable": false
                            },
                            {
                                "data": "change",
                                "className": "govuk-table__cell r",
                                "orderable": false
                            }
                        ]
                    });
                </script>

            </div>
    </div>


    </main>
    </div>
    <?php
    require("../includes/footer_neutral.php");
    ?>
</body>

</html>