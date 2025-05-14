<?php
header('Content-Type: application/json');
require_once "../../Model/delete.php";

try {
    // Parse from URL for actual DELETE requests
    parse_str($_SERVER['QUERY_STRING'], $params);
    if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' || !isset($params['table']) || !isset($params['id'])) {
        throw new Exception("Invalid request method or missing required parameters");
    }

    $tableName = $params['table'];
    $id = $params['id'];

    $deleteModel = new DeleteClass();
    $result = $deleteModel->delete($tableName, $id);

    echo json_encode([
        'success' => true,
        'message' => 'Record deleted successfully'
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
