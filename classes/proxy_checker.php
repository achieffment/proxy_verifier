<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/classes/proxy_constructor.php";

class ProxyChecker extends Proxy {

    public function __construct($db_conn, $db_tables)
    {
        parent::__construct($db_conn, $db_tables);
    }

    // Adding proxy in death proxy list
    public function addDeath($proxy) {
        return $this->add($proxy, 1);
    }

    // Adding proxy in totally death proxy list
    public function addTotallyDeath($proxy) {
        return $this->add($proxy, 2);
    }

    // Get count from death proxy list by proxy
    public function getDeathProxyCountByDeathProxy($proxy) {
        $query = "SELECT * FROM {$this->db_tables[1]} WHERE proxy = '{$proxy}'";
        $result = $this->db_conn->query($query);
        if ($result) {
            return $result->num_rows;
        } else
            $this->setResponse("Error getting death proxy count", true);
    }

    // Verifying proxy in lists
    // If count of proxy in death proxy less than 4, it adds one more
    // If count more than 3, it deletes proxy from main list and death list and adds in totally death list
    public function verifyDeathProxy($proxy) {
        $proxy = $this->validateProxy($proxy);
        if ($proxy !== false) {
            $proxyCount = $this->getDeathProxyCountByDeathProxy($proxy);
            if ($proxyCount !== false) {
                if ($proxyCount > 2) {
                    if ($this->remove($proxy, 0))
                        if ($this->remove($proxy, 1))
                            if ($this->addTotallyDeath($proxy))
                                return true;
                } else
                    if ($this->addDeath($proxy))
                        return true;
            }
        }
        $this->setResponse("on verifying death proxy", false, true, true);
        return false;
    }

}

?>