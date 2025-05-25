<?php
require_once "../Model/autoload.php";
require_once "../Model/config.php";
require_once "../Controllers/AdminController.php";

// Parse the URL to determine what resource to load
$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);
$path = substr($path, strlen('/clinicus/admin/')); // Remove the admin_path from the beginning of the path

$controller = new AdminController();

// Define valid table names that have corresponding models
$validTables = [
    'users',
    'usertype',
    'doctors',
    'patients',
    'appointments',
    'medical_history',
    'medications',
    'prescriptions',
    'staff',
    'audit_logs'
];

switch ($path) {
    case '':
    case '/':
        $controller->index();
        break;
    default:
        $segments = explode('/', trim($path, '/')); // split by the slash to find table name
        if (count($segments) > 0) {
            $tableName = $segments[0];
            if (in_array($tableName, $validTables)) {
                $controller->manageTable($tableName);
            } else {
                // Handle invalid table name
                header("Location: /clinicus/admin?error=invalid_table");
                exit;
            }
        } else {
            $controller->index();
        }
        break;
}
