<?php
require_once "../../Model/config.php";
require_once "../../Model/autoload.php";

use Model\entities\ModelFactory;

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['table']) || !isset($_POST['id'])) {
        throw new Exception("Invalid request method or missing required parameters");
    }

    $tableName = $_POST['table'];
    $id = $_POST['id'];

    $db = (new DatabaseConnection())->connectToDB();
    $model = ModelFactory::getModelInstance($tableName, $db);
    $result = $model->delete($id);

    if ($result) {
        header("Location: /clinicus/{$tableName}?success=deleted");
    } else {
        header("Location: /clinicus/{$tableName}?error=delete_failed");
    }
} catch (Exception $e) {
    header("Location: /clinicus/{$tableName}?error=" . urlencode($e->getMessage()));
}
