<?php
require_once "../../Model/config.php";
require_once "../../Model/autoload.php";

use Model\entities\ModelFactory;

try {
    if (!isset($_GET['table']) || !isset($_GET['id'])) {
        throw new Exception("Missing required parameters");
    }

    $tableName = $_GET['table'];
    $id = $_GET['id'];

    $db = (new DatabaseConnection())->connectToDB();
    $model = ModelFactory::getModelInstance($tableName, $db);
    $record = $model->getById($id);

    if ($record) {
        header('Content-Type: application/json');
        echo json_encode($record);
    } else {
        throw new Exception("Record not found");
    }
} catch (Exception $e) {
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}
