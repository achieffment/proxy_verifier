<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/init/db_conn.php"; ?>
<?php require_once $serverPath . "classes/proxy_constructor.php"; ?>
<?php require_once $serverPath . "classes/proxy_checker.php"; ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/assets/style.css">
    <title></title>
</head>
<body>
    <?php
        $proxyChecker = new ProxyChecker($db_conn, $db_tables);
        if (isset($_GET["add_death"]) && $_GET["add_death"]) {
            // If we have get request "add_death" it checks count of proxy in death list, if that less than 4, it adds one more in this list
            // If proxy count more than 4, it deletes proxy from main list, death list, and adds in totally death list
            $proxyChecker->verifyDeathProxy($_GET["add_death"]);
        } else if (isset($_GET["getall_proxy"])) {
            // If we have get request "getall_proxy" it shows all proxy from main list
            $proxyChecker->getAllAndShow(0);
        } else if (isset($_GET["addlist_proxy"])) {
            // If we have get request "addlist_proxy" it shows file /pages/addlist_proxy.php
            require_once $serverPath . "pages/addlist_proxy.php";
        }
    ?>
</body>
</html>