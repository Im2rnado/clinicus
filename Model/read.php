<?php

require_once "config.php";

class ReadClass
{
    private $db;
    private $link;

    public function __construct()
    {
        $this->db = new DatabaseConnection();
        $this->link = $this->db->connectToDB();
    }

    public function readAll($tableName)
    {
        $sql = "SELECT * FROM $tableName";
        if ($result = mysqli_query($this->link, $sql)) {
            $records = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $records[] = $row;
            }
            return $records;
        } else {
            throw new Exception("Query failed: " . mysqli_error($this->link));
        }
    }

    public function readOne($tableName, $id)
    {
        $sql = "SELECT * FROM $tableName WHERE Id=?";
        if ($stmt = mysqli_prepare($this->link, $sql)) {
            mysqli_stmt_bind_param($stmt, "i", $id);
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                if ($row = mysqli_fetch_assoc($result)) {
                    mysqli_stmt_close($stmt);
                    return $row;
                } else {
                    mysqli_stmt_close($stmt);
                    throw new Exception("Record not found");
                }
            } else {
                throw new Exception("Execution failed: " . mysqli_error($this->link));
            }
        } else {
            throw new Exception("Preparation failed: " . mysqli_error($this->link));
        }
    }
}
