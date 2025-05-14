<?php
require_once "../../Model/update.php";

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['table']) || !isset($_POST['id'])) {
        throw new Exception("Invalid request method or missing required parameters");
    }

    $tableName = $_POST['table'];
    $id = $_POST['id'];

    // Remove the table and id fields from POST data
    $data = $_POST;
    unset($data['table']);
    unset($data['id']);

    $updateModel = new UpdateClass();
    $result = $updateModel->updateRecord($tableName, $id, $data);

    if ($result) {
        header("Location: /clinicus/admin/{$tableName}?success=updated");
    } else {
        header("Location: /clinicus/admin/{$tableName}?error=update_failed");
    }
} catch (Exception $e) {
    header("Location: /clinicus/admin/{$tableName}?error=" . urlencode($e->getMessage()));
}
