<?php

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'clinicus');

// Application configuration
define('APP_NAME', 'Clinicus');
define('APP_URL', 'http://localhost/clinicus');
define('APP_ROOT', dirname(__DIR__));

// Session configuration
define('SESSION_LIFETIME', 3600); // 1 hour
ini_set('session.gc_maxlifetime', SESSION_LIFETIME);
session_set_cookie_params(SESSION_LIFETIME);

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Time zone
date_default_timezone_set('UTC');

// Security
define('CSRF_TOKEN_SECRET', 'your-secret-key-here');
define('PASSWORD_HASH_COST', 12);

// Language configuration
define('DEFAULT_LANGUAGE', 'en');
define('AVAILABLE_LANGUAGES', ['en', 'ar']);

// Pagination
define('ITEMS_PER_PAGE', 10);



class DatabaseConnection
{
    private $server = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "clinicus_db";
    private $port = 3307;

    public function connectToDB()
    {
        $link = mysqli_connect($this->server, $this->username, $this->password, $this->dbname, $this->port);
        if (!$link) {
            throw new Exception("ERROR: Could not connect. " . mysqli_connect_error());
        }
        return $link;
    }
}
