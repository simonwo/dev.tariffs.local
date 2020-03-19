<?php
global $measure_activity;
?>
<div class="govuk-grid-row">
    <div class="govuk-grid-column-full">
        <link rel="stylesheet" href="/css/pqgrid.dev.css" />
        <script src="/grid-2.4.1/pqgrid.dev.js"></script>
        <link rel="stylesheet" href="/css/themes/govuk/pqgrid.css" />
        <script>
            url = '/api/v1/measure_activities/index.php?measure_activity_sid=<?= $measure_activity->measure_activity_sid ?>';

            var text = "";
            $.ajaxSetup({
                async: false
            });
            $.getJSON(url, (data) => {
                text = data;
            });
            $.ajaxSetup({
                async: true
            });

            var count = text.data.length;
            colModel = [];

            if (count > 0) {
                var prototype = text.data[0];
                var non_editable_fields = ["commodity_code", "additional_code"];
                column_count = Object.keys(prototype).length;
                column_width = Math.floor(100 / column_count);
                column_width_standard = column_width + "%";
                column_width_last = (column_width - 1) + "%";

                index = 0;
                Object.keys(prototype).forEach(function(item) {
                    index++;
                    if ((item == "commodity_code") || (item == "additional_code")) {
                        title = fmt_title(item);
                    } else {
                        title = item;
                    }
                    var column = new Array();
                    column["title"] = title;
                    column["dataType"] = "string";
                    column["dataIndx"] = item;

                    /*
                    if ((item != "commodity_code") && (item != "additional_code")) {
                        column["render"] = function(ui) {
                            //return (parse_duty_value(ui.cellData));
                            //return "$" + parseFloat(ui.cellData).toFixed(2);
                            if (ui.cellData == null) {
                                return "";
                            } else {
                                return "$" + parse_duty_value(ui.cellData);
                            }
                            
                        }

                    }
                    */


                    if (index < column_count) {
                        column["width"] = column_width_standard;
                    } else {
                        column["width"] = column_width_last;
                    }

                    if (non_editable_fields.indexOf(item) == -1) {
                        column["editable"] = true;
                    } else {
                        column["editable"] = false;
                    }

                    colModel.push(column);
                });
            }

            $(function() {
                var obj = {
                    minWidth: 500,
                    height: 400,
                    autofill: true,
                    fillHandle: "both",
                    resizable: true,
                    scrollModel: {
                        autoFit: true
                    },
                    showTop: false,
                    showBottom: false,
                    numberCell: {
                        show: true
                    },
                    title: "",
                    collapsible: {
                        on: true,
                        collapsed: false
                    },
                };
                obj.colModel = colModel;

                obj.dataModel = {
                    location: "remote",
                    dataType: "jsonp",
                    method: "GET",
                    url: "/api/v1/measure_activities/index.php?measure_activity_sid=<?= $measure_activity->measure_activity_sid ?>",
                    getData: function(dataJSON) {
                        var data = dataJSON.data;
                        return {
                            curPage: dataJSON.curPage,
                            totalRecords: dataJSON.totalRecords,
                            data: data
                        };
                    }
                };

                var $grid = $("#grid_json").pqGrid(obj);
                //$("#grid_json").pqGrid("option", "height", '110%');


                //bind width to select list.
                $("#sl_width").change(function(evt) {
                    var val = $(this).val();
                    $grid.pqGrid('option', 'width', val)
                        .pqGrid('refresh');
                });
                $("#sl_margin").change(function(evt) {
                    var val = $(this).val();
                    $grid.css('margin', val).pqGrid('refresh');
                });
            });
        </script>

        <?php
        global $measure_activity;

        foreach ($measure_activity->additional_code_list as $additional_code) {
            if ($additional_code->additional_code == "999") {
        ?>
                <script>
                    $(document).ready(function() {
                        $("#residual_additional_code").val("<?= $additional_code->code ?>");
                    });
                </script>
            <?php
            }
        }

        if ($measure_activity->measure_component_applicable_code == 2) {
            new inset_control(
                $text = "You have selected a measure type which cannot have duties associated with it. Click on the 'Continue ...' button below to enter measure conditions."
            );
        } else {
            new inset_control(
                $text = "Use this form to enter the duties that will apply to the measures for the previously selected commodity codes.<br /><br /><span class='inset_1'>Workbasket:</span><span class='highlighted_text inset_2'>{workbasket_name}</span><span class='inset_1'>Activity:</span><span class='highlighted_text inset_2'>{activity_name}</span>"
            );
            ?>
            <div id="grid_json" style="margin:20px auto;"></div>

        <?php
        }
        ?>
    </div>
</div>