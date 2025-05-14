<?php
header('Content-Type: application/json');
require_once "../../Model/read.php";

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET' || !isset($_GET['table']) || !isset($_GET['id'])) {
        throw new Exception("Invalid request method or missing required parameters");
    }

    $tableName = $_GET['table'];
    $id = $_GET['id'];

    $readModel = new ReadClass();
    $record = $readModel->readOne($tableName, $id);

    echo json_encode([
        'success' => true,
        'record' => $record
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
