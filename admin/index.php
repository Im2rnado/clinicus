<?php
require_once "../Controllers/AdminController.php";

// Parse the URL to determine what resource to load
$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);
$path = substr($path, strlen('/clinicus/admin/')); // Remove the admin_path from the beginning of the path

$controller = new AdminController();


switch ($path) {
    case '':
    case '/':
        $controller->index();
        break;
    case 'users':
    case 'usertype':
    case 'doctors':
    case 'patients':
    case 'appointments':
    case 'medical_history':
    case 'medications':
    case 'prescriptions':
    case 'staff':
    case 'audit_logs':
        $controller->manageTable($path);
        break;
    default:
        $segments = explode('/', trim($path, '/')); // split by the slash to find table name
        if (count($segments) > 0) {
            $tableName = $segments[0];
            $controller->manageTable($tableName);
        } else {
            $controller->index();
        }
        break;
}
