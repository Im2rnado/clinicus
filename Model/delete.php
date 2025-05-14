<?php

require_once "config.php";

class DeleteClass
{
    private $db;
    private $link;

    public function __construct()
    {
        $this->db = new DatabaseConnection();
        $this->link = $this->db->connectToDB();
    }

    public function delete($tableName, $id, $userId = null)
    {
        $sql = "DELETE FROM $tableName WHERE Id=?";
        if ($stmt = mysqli_prepare($this->link, $sql)) {
            mysqli_stmt_bind_param($stmt, "i", $id);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);

                // Log the action to audit_logs
                $this->logAction($userId, "DELETE from $tableName with ID: $id");

                return true;
            } else {
                throw new Exception("Execution failed: " . mysqli_error($this->link));
            }
        } else {
            throw new Exception("Preparation failed: " . mysqli_error($this->link));
        }
    }

    private function logAction($userId, $action)
    {
        $timestamp = date('Y-m-d H:i:s');
        $userId = $userId ?? 1; // Use 1 if userId is not provided

        $sql = 'INSERT INTO audit_logs (UserId, Action, Timestamp) VALUES (?, ?, ?)';

        if ($stmt = mysqli_prepare($this->link, $sql)) {
            mysqli_stmt_bind_param($stmt, "iss", $userId, $action, $timestamp);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }
}
