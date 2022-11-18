<?php

    $db_hostname = "localhost";
    $db_login    = "root";
    $db_password = "";
    $db_name     = "proxy";

    $serverPath = $_SERVER["DOCUMENT_ROOT"] . "/";

    // Names of db tables
    // All tables consists of two columns: id (int AUTO_INCREMENT), proxy (VARCHAR 50)
    $db_tables = [
        "proxy",
        "proxy_death",
        "proxy_totally_death"
    ];

    $db_conn = new mysqli($db_hostname, $db_login, $db_password, $db_name);
    if ($db_conn->connect_error)
        die ("Connection problem with db: " . $db_conn->connect_error);

?>