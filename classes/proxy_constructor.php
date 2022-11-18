<?php

class Proxy {

    public $status_message = "";

    public function __construct($db_conn, $db_tables)
    {
        $this->db_conn = $db_conn; // giving a db connection variable
        $this->db_tables = $db_tables; // "proxy", "proxy_death", "proxy_totally_death" - list of tables in db
    }

    // Adding proxy in db table by table number, table number give a table name from $db_tables array
    public function add($proxy, $table_number)
    {
        $proxy = $this->validateProxy($proxy);
        if ($proxy !== false) {
            $query = "INSERT INTO " . $this->db_tables[$table_number] . " (`proxy`) VALUES('{$proxy}')";
            $result = $this->db_conn->query($query);
            if ($result)
                return true;
            else
                $this->setResponse("Error with adding proxy", true);
        } else
            $this->setResponse("on adding proxy", false, true);
        return false;
    }

    // Add a few proxies by table number, table number give a table name from $db_tables array
    public function addList($proxies, $table_number)
    {
        foreach ($proxies as $proxy) {
            if (!$this->add($proxy, $table_number))
                return false;
        }
        return true;
    }

    // Get all proxies from db by table number, table number give a table name from $db_tables array
    public function getAll($table_number) {
        $query = "SELECT * FROM {$this->db_tables[$table_number]}";
        $result = $this->db_conn->query($query);
        if ($result) {
            if ($result->num_rows) {
                $proxies = [];
                while ($row = $result->fetch_assoc())
                    $proxies[] = $row["proxy"];
                return $proxies;
            } else
                $this->setResponse("List is clear");
        } else
            $this->setResponse("Error with select all proxies", true);
        return false;
    }

    // Show list of proxies
    public function showAll($proxies) {
        for ($i = 0; $i < count($proxies); $i++)
            echo "<p>" . $proxies[$i] . "</p>";
    }

    // Get proxies by table number, table number give a table name from $db_tables array, and show
    public function getAllAndShow($table_number) {
        $proxies = $this->getAll($table_number);
        if ($proxies !== false) {
            $this->showAll($proxies);
            return true;
        }
        $this->setResponse("on getting all proxy", false, true, true);
        return false;
    }

    // Remove proxy by proxy and table number, table number give a table name from $db_tables array
    public function remove($proxy, $table_number)
    {
        $query = "DELETE FROM " . $this->db_tables[$table_number] . " WHERE proxy = '{$proxy}'";
        $result = $this->db_conn->query($query);
        if ($result)
            return true;
        $this->setResponse("Error with deleting proxy", true);
        return false;
    }

    // Validate proxy, proxy consists only from numbers, dots and colons
    public function validateProxy($proxy) {
        $proxy = htmlspecialchars(strip_tags($proxy));
        preg_match_all("/[0-9\.:]/", $proxy, $matches);
        if ($matches) {
            $proxy = implode("", $matches[0]);
            if ($proxy && strlen($proxy) <= 50)
                return $proxy;
            else if ($proxy && strlen($proxy) > 50)
                $this->setResponse("Proxy is more than 50 symbols");
            else
                $this->setResponse("Proxy is empty after implode");
        } else
            $this->setResponse("Proxy came empty");
        return false;
    }

    // Validate list of proxies
    public function validateListProxy($proxies) {
        $proxies = htmlspecialchars(strip_tags($proxies));
        $proxies = str_replace(" ", "", $proxies);
        if ($proxies)
            return $proxies;
        $this->setResponse("Proxy came cleared");
        return false;
    }

    // Validate list of proxies and make it as array, supposed that proxies must come with one proxy in one string, like in txt file
    public function validateListExplodeAddListProxies($proxies, $table_number) {
        $proxies = $this->validateListProxy($proxies);
        if ($proxies !== false) {
            $proxies = explode("\r\n", $proxies);
            $proxies = array_unique($proxies);
            return $this->addList($proxies, $table_number);
        }
        $this->setResponse("on adding list proxies", false, true, true);
        return false;
    }

    // Set response, has a few options to show db errors, appends existing message and writing log
    public function setResponse($message, $db_error = false, $append_message = false, $writeLog = false) {
        if ($append_message)
            $message = $this->status_message . " / " . $message;
        if ($db_error)
            $message = $message . " / " . $this->db_conn->error;
        $this->status_message = $message;
        if ($writeLog)
            $this->writeErrorLog(true);
    }

    // Writes a log in root of server, adding time to a message, by defaults take a status message, but can used by separate message
    public function writeErrorLog($useStatusMessage = true, $message = "") {
        $date = date("d-m-Y H:i:s");
        $full_message = $date . " : ";
        if ($useStatusMessage)
            $full_message .= $this->status_message . "\n";
        else
            $full_message .= $message . "\n";
        file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/log.txt", $message, FILE_APPEND);
    }

}

?>