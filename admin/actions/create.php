<?php
require_once "../../Model/create.php";

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['table'])) {
        throw new Exception("Invalid request method or missing required parameters");
    }

    $tableName = $_POST['table'];

    // Remove the table field from POST data
    $data = $_POST;
    unset($data['table']);

    $createModel = new CreateClass();
    $result = $createModel->insertRecord($tableName, data: $data);

    if ($result) {
        header("Location: /clinicus/admin/{$tableName}?success=created");
    } else {
        header("Location: /clinicus/admin/{$tableName}?error=creation_failed");
    }
} catch (Exception $e) {
    header("Location: /clinicus/admin/{$_POST['table']}?error=" . urlencode($e->getMessage()));
}
