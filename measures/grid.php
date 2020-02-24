<!DOCTYPE html>
<html lang="en" class="govuk-template">

<head>
    <meta charset="utf-8" />
    <title>Measures : Manage the UK Tariff</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#0b0c0c" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" sizes="16x16 32x32 48x48" href="/assets/images/favicon.ico" type="image/x-icon" />
    <link rel="mask-icon" href="/assets/images/govuk-mask-icon.svg" color="#0b0c0c">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/images/govuk-apple-touch-icon-180x180.png">
    <link rel="apple-touch-icon" sizes="167x167" href="/assets/images/govuk-apple-touch-icon-167x167.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/assets/images/govuk-apple-touch-icon-152x152.png">
    <link rel="apple-touch-icon" href="/assets/images/govuk-apple-touch-icon.png">
    <!--[if !IE 8]><!-->
    <link href="/css/govuk-frontend-3.4.0.min.css" rel="stylesheet" />
    <link href="/css/application.css" rel="stylesheet" />
    <!--<![endif]-->
    <script src="/js/jquery-3.4.1.min.js"></script>
    <script src="/js/jquery-ui.min.js"></script>
    <script src="/js/js.cookie.js"></script>
    <script src="/js/typeahead.bundle.js"></script>
    <link href="/css/select2.css" rel="stylesheet" />
    <script src="/js/select2.js"></script>
    <script src="/js/application.js"></script>
    <script src="/js/date.format.js"></script>
    <script src="/js/cursor.js"></script>
    <link rel="stylesheet" href="/css/pqgrid.min.css" />
        <script src="/grid-2.4.1/pqgrid.dev.js"></script>
        <link rel="stylesheet" href="/css/themes/govuk/pqgrid.css" />


    <!--[if IE 8]>
<link href="/css/govuk-frontend-ie8-3.4.0.min.css" rel="stylesheet" />
<link href="/css/govuk-frontend-ie8-3.4.0.min.css" rel="stylesheet" />
<![endif]-->

    <!--[if lt IE 9]>
<script src="/html5-shiv/html5shiv.js"></script>
<![endif]-->

    <meta property="og:image" content="/assets/images/govuk-opengraph-image.png">
</head>

<body class="govuk-template__body">
    <script>
        document.body.className = ((document.body.className) ? document.body.className + ' js-enabled' : 'js-enabled');
    </script>

<script>
    $(function () {
        var colModel= [
            { title: "Product ID", dataType: "integer", dataIndx: "ProductID", editable: false, width: 80 },
            { title: "Product Name", width: 165, dataType: "string", dataIndx: "ProductName" },
            { title: "Quantity Per Unit", width: 140, dataType: "string", align: "right", dataIndx: "QuantityPerUnit" },
            { title: "Unit Price", width: 100, dataType: "float", align: "right", dataIndx: "UnitPrice" },                
            { title: "Units In Stock", width: 100, dataType: "integer", align: "right", dataIndx: "UnitsInStock" },
            { title: "Discontinued", width: 100, dataType: "bool", align: "center", dataIndx: "Discontinued" }
        ];
        var dataModel = {
            location: "remote",
            dataType: "jsonp",
            method: "GET",
            url: "https://paramquery.com/pro/products/get_jsonp",
            //url: "/pro/products.php",//for PHP
            getData: function (dataJSON) {
                var data = dataJSON.data;
                return { curPage: dataJSON.curPage, totalRecords: dataJSON.totalRecords, data: data };
            }
        };

        $("#grid_jsonp").pqGrid({
            height: 450,
            scrollModel: {autoFit: true},
            dataModel: dataModel,
            colModel: colModel,                                                            
            numberCell: { resizable: true, width: 30, title: "#" },
            title: "Products",
            resizable: true
        });
    });
</script>

<div id="grid_jsonp" style="margin:5px auto;"></div>

</body>

</html>