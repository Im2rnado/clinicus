<?php
// Model/entities/Department.php

class Department
{
    public $ID;
    public $name;
    public $createdAt;
    public $updatedAt;
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($data)
    {
        $stmt = $this->conn->prepare("INSERT INTO Department (name) VALUES (?)");
        $stmt->bind_param("s", $data['name']);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function read($id = null)
    {
        if ($id === null) {
            $result = $this->conn->query("SELECT * FROM Department");
            $departments = [];
            while ($row = $result->fetch_object()) {
                $departments[] = $row;
            }
            return $departments;
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM Department WHERE ID = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            $department = $res->fetch_object();
            $stmt->close();
            return $department;
        }
    }

    public function update($id, $data)
    {
        $stmt = $this->conn->prepare("UPDATE Department SET name = ? WHERE ID = ?");
        $stmt->bind_param("si", $data['name'], $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM Department WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}