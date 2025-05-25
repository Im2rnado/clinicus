<?php

class DatabaseConnection {
    private $server = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "clinicus_db";
    private $port = 3307;

    public function connectToDB() {
        $link = mysqli_connect($this->server, $this->username, $this->password, $this->dbname, $this->port);
        if (!$link) {
            throw new Exception("ERROR: Could not connect. " . mysqli_connect_error());
        }
        return $link;
    }
}
