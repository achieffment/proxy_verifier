<?php

    // For creating db and tables
    $db_hostname = "localhost";
    $db_login = "root";
    $db_pass = "";
    $db_name = "proxy";

    $db_tables = [
        "'proxy'",
        "'proxy_death'",
        "'proxy_totally_death'"
    ];

    $db_conn = new mysqli($db_hostname, $db_login, $db_pass);
    if ($db_conn->connect_error)
        die("Connection with db failed: " . $db_conn->connect_error);

    // Checking and creating database for project
    $query = "SHOW DATABASES LIKE '%{$db_name}%'";
    $result = $db_conn->query($query);
    if ($result) {
        if ($result->num_rows == 0) {
            $dbs = [];
            while ($row = $result->fetch_assoc())
                $dbs[] = $row;
            if (!in_array($db_name, $dbs)) {
                $query = "CREATE DATABASE {$db_name}";
                $result = $db_conn->query($query);
                if (!$result)
                    die("Problem with creating database: " . $db_conn->error);
            }
        }
    } else
        die("Problem with finding database: " . $db_conn->error);

    // Selecting database
    if (!$db_conn->select_db($db_name))
        die("Can not access database");

    // Taking tables
    $query = "SELECT table_name FROM information_schema.tables WHERE table_schema = '{$db_name}' AND table_name IN (" . implode(",", $db_tables) . ")";
    $result = $db_conn->query($query);
    if ($result) {
        $tables = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $tables[] = $row["TABLE_NAME"];
            }
        }
    } else
        die("Problem with checking tables: " . $db_conn->error);

    // Checking and creating tables
    if (!$tables || count($tables) < count($db_tables)) {
        foreach ($db_tables as $db_table) {
            $db_table_name = str_replace("'", "", $db_table);
            if (!in_array($db_table_name, $tables)) {
                $query = "CREATE TABLE {$db_table_name} (
                    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    proxy VARCHAR(50) NOT NULL
                )";
                $result = $db_conn->query($query);
                if (!$result)
                    die("Problem with creating table {$db_table_name}: " . $db_conn->error);
            }
        }
    }

?>