<?php
require_once "../../Model/config.php";
require_once "../../Model/entities/ModelFactory.php";

use Model\entities\ModelFactory;

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['table'])) {
        throw new Exception("Invalid request method or missing required parameters");
    }

    $tableName = $_POST['table'];
    $data = $_POST;
    unset($data['table']);

    $db = (new DatabaseConnection())->connectToDB();
    $model = ModelFactory::getModelInstance($tableName, $db);
    $result = $model->create($data);

    if ($result) {
        header("Location: /clinicus/admin/{$tableName}?success=created");
    } else {
        header("Location: /clinicus/admin/{$tableName}?error=creation_failed");
    }
} catch (Exception $e) {
    header("Location: /clinicus/admin/{$_POST['table']}?error=" . urlencode($e->getMessage()));
}
