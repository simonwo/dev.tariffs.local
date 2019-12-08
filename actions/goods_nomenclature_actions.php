<?php
    require (dirname(__FILE__) . "../../includes/db.php");
    $phase = get_formvar("phase");
    pre($_REQUEST);
    //exit();

    if ($phase == "goods_nomenclature_item_view") {
        get_formvars_goods_nomenclature_item_view();

    } elseif ($phase == "goods_nomenclature_item_edit") {
        get_formvars_goods_nomenclature_edit();
    
    } elseif ($phase == "goods_nomenclature_item_view_filter") {
        get_formvars_goods_nomenclature_item_view_filter();

    } elseif ($phase == "goods_nomenclature_description_delete") {
        get_formvars_goods_nomenclature_description_delete();

    } elseif ($phase == "goods_nomenclature_update_description") {
        get_formvars_goods_nomenclature_update_description();
        exit();
    } elseif ($phase == "goods_nomenclature_edit") {
        get_formvars_goods_nomenclature_edit();
        exit();
    }
    
    function get_formvars_goods_nomenclature_edit() {
        $action = get_querystring("action");
        h1 ($action);
        switch ($action) {
            case "add":
                $url  = "/goods_nomenclature_create.html";
                header("Location: " . $url);
                break;
            case "dates":
                $url  = "/goods_nomenclature_dates.html";
                header("Location: " . $url);
                break;
            case "delete":
                $url  = "/goods_nomenclature_delete.html";
                header("Location: " . $url);
                break;
        }
        #exit();
    
    }
    
    function xget_formvars_goods_nomenclature_edit() {
        $goods_nomenclature_item_id = get_querystring("goods_nomenclature_item_id");
        $goods_nomenclature_sid = get_querystring("goods_nomenclature_item_id");
        $productline_suffix = get_querystring("productline_suffix");
        $url  = "/goods_nomenclature_item_edit_phase1.html";
        $url .= "?goods_nomenclature_sid=" . $goods_nomenclature_sid;
        $url .= "&goods_nomenclature_item_id=" . $goods_nomenclature_item_id;
        $url .= "&productline_suffix=" . $productline_suffix;
        #echo ($url);
        #exit();
        header("Location: " . $url);

    }

    function get_formvars_goods_nomenclature_item_view() {
        $goods_nomenclature_item_id = get_querystring("goods_nomenclature_item_id");
        $goods_nomenclature_item_id = str_replace(" ", "", $goods_nomenclature_item_id);
        if (strlen($goods_nomenclature_item_id) < 10) {
            $goods_nomenclature_item_id .= str_repeat("0", 10 - strlen($goods_nomenclature_item_id));
        }

        $url  = "/goods_nomenclature_item_view.html";
        $url .= "?goods_nomenclature_item_id=" . $goods_nomenclature_item_id;
        #echo ($url);
        #exit();
        header("Location: " . $url);

    }

    function get_formvars_goods_nomenclature_description_delete() {
        $goods_nomenclature = new goods_nomenclature;
        $goods_nomenclature->goods_nomenclature_description_period_sid = get_querystring("goods_nomenclature_description_period_sid");
        $goods_nomenclature->goods_nomenclature_item_id = get_querystring("goods_nomenclature_item_id");
        $goods_nomenclature->productline_suffix = get_querystring("productline_suffix");
        $goods_nomenclature->delete_description();
        $url = "/goods_nomenclature_item_view.html?goods_nomenclature_item_id=" . $goods_nomenclature->goods_nomenclature_item_id . "&productline_suffix=" . $goods_nomenclature->productline_suffix . "#description_periods";
        header("Location: " . $url);            
    }


    function get_formvars_goods_nomenclature_item_view_filter() {
        $geographical_area_id = get_querystring("geographical_area_id");
        $geographical_area_id = strtoupper($geographical_area_id);
        $measure_type_id = get_querystring("measure_type_id");
        $goods_nomenclature_item_id = get_querystring("goods_nomenclature_item_id");
        $goods_nomenclature_item_id = str_replace(" ", "", $goods_nomenclature_item_id);
        if (strlen($goods_nomenclature_item_id) < 10) {
            $goods_nomenclature_item_id .= str_repeat("0", 10 - strlen($goods_nomenclature_item_id));
        }

        $url  = "/goods_nomenclature_item_view.html";
        $url .= "?goods_nomenclature_item_id=" . $goods_nomenclature_item_id;
        $url .= "&measure_type_id=" . $measure_type_id;
        $url .= "&geographical_area_id=" . $geographical_area_id;
        $url .= "#assigned";
        #echo ($url);
        #exit();
        header("Location: " . $url);

    }
    function get_formvars_goods_nomenclature_update_description() {
        $errors = [];
        $goods_nomenclature_item_id                 = get_formvar("goods_nomenclature_item_id");
        $goods_nomenclature_sid                     = get_formvar("goods_nomenclature_sid");
        $productline_suffix                         = get_formvar("productline_suffix");
        $goods_nomenclature_description_period_sid  = get_formvar("goods_nomenclature_description_period_sid");
        $validity_start_day                         = get_formvar("validity_start_day",     "goods_nomenclature_", True);
        $validity_start_month                       = get_formvar("validity_start_month",   "goods_nomenclature_", True);
        $validity_start_year                        = get_formvar("validity_start_year",    "goods_nomenclature_", True);
        $description                                = get_formvar("description",            "goods_nomenclature_", True);

        $validity_start_date    = to_date_string($validity_start_day, $validity_start_month, $validity_start_year);

        if ($goods_nomenclature_description_period_sid == -1) {
            # Check on the validity start and end dates
            $valid_start_date = checkdate($validity_start_month, $validity_start_day, $validity_start_year);
            #h1 ("valid start date" . $valid_start_date);
            #exit();
            if ($valid_start_date != 1) {
                array_push($errors, "validity_start_date");
            }
        }

        # Check on description
        if ($description == "") {
            array_push($errors, "description");
        }
        #h1 (count($errors));
        #exit();

        if (count($errors) == 0) {
            $goods_nomenclature = new goods_nomenclature;
            if ($goods_nomenclature_description_period_sid != -1) {
                $ret = $goods_nomenclature->update_description($goods_nomenclature_item_id, $productline_suffix, $goods_nomenclature_sid, $validity_start_date, $description, $goods_nomenclature_description_period_sid);
            } else {
                $ret = $goods_nomenclature->insert_description($goods_nomenclature_item_id, $productline_suffix, $goods_nomenclature_sid, $validity_start_date, $description);
                if (is_array($ret)) {
                    array_push($errors, "conflict_with_existing");
                }    
            }
        }

        if (count($errors) > 0) {
            $error_string = serialize($errors);
            setcookie("errors", $error_string, time() + (86400 * 30), "/");
            $url = "/goods_nomenclature_add_description.html?action=new&err=1&goods_nomenclature_item_id=" . $goods_nomenclature_item_id . "&productline_suffix=" . $productline_suffix;
        } else {
            $url = "/goods_nomenclature_item_view.html?goods_nomenclature_item_id=" . $goods_nomenclature_item_id . "&productline_suffix=" . $productline_suffix . "#description_periods";
        }
        header("Location: " . $url);

    }

?>