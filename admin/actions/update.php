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
    $data = $_POST;
    unset($data['table']);
    unset($data['id']);

    $db = (new DatabaseConnection())->connectToDB();
    $model = ModelFactory::getModelInstance($tableName, $db);
    $result = $model->update($id, $data);

    if ($result) {
        header("Location: /clinicus/admin/{$tableName}?success=updated");
    } else {
        header("Location: /clinicus/admin/{$tableName}?error=update_failed");
    }
} catch (Exception $e) {
    header("Location: /clinicus/admin/{$tableName}?error=" . urlencode($e->getMessage()));
}
