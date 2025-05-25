<?php
// Model/entities/Unit.php

class Unit
{
    public $ID;
    public $Name;
    public $createdAt;
    public $updatedAt;
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($data)
    {
        $stmt = $this->conn->prepare("INSERT INTO Unit (Name) VALUES (?)");
        $stmt->bind_param("s", $data['Name']);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function read($id = null)
    {
        if ($id === null) {
            $result = $this->conn->query("SELECT * FROM Unit");
            $units = [];
            while ($row = $result->fetch_object()) {
                $units[] = $row;
            }
            return $units;
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM Unit WHERE ID = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            $unit = $res->fetch_object();
            $stmt->close();
            return $unit;
        }
    }

    public function update($id, $data)
    {
        $stmt = $this->conn->prepare("UPDATE Unit SET Name = ? WHERE ID = ?");
        $stmt->bind_param("si", $data['Name'], $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM Unit WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}