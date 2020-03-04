<html>

<body>
    <?php

    require("includes/p.php");


    pre($_SERVER);
    $server_name = $_SERVER["SERVER_NAME"];

    if ($server_name == "tariff-prototype.london.cloudapps.digital") {
        $dbCredentialsUrl = $_ENV['DATABASE_URL'];
        $credentials = parse_url($dbCredentialsUrl);
        pre($dbCredentialsUrl);
        pre($credentials);

        $host = $credentials['host'];
        $dbase = trim($credentials['path'], '/');
        $dbuser = $credentials['user'];
        $pwd = $credentials['pass'];
    } else {
        $host = $host_local;
        $dbase = $dbase_local;
        $dbuser = $dbuser_local;
        $pwd = $pwd_local;
    }


    $conn = pg_connect("host=" . $host . " port=5432 dbname=" . $dbase . " user=" . $dbuser . " password=" . $pwd);

    pre($conn);
    $sql = "select position, numeral, title from sections s order by 1;";
    $result = pg_query($conn, $sql);
    if ($result) {
        while ($row = pg_fetch_array($result)) {
            echo ($row["position"] . ", " . $row["numeral"] . ", " . $row["title"]);
        }
    }
    ?>
    <h1>Here</h1>
</body>

</html>
<?php
function pre($data)
{
    print '<pre>' . print_r($data, true) . '</pre>';
}
?>