<?php
// Model/entities/BloodType.php

class BloodType
{
    public $ID;
    public $Type;
    public $createdAt;
    public $updatedAt;
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($data)
    {
        $stmt = $this->conn->prepare("INSERT INTO Blood_Type (Type) VALUES (?)");
        $stmt->bind_param("s", $data['Type']);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function read($id = null)
    {
        if ($id === null) {
            $result = $this->conn->query("SELECT * FROM Blood_Type");
            $types = [];
            while ($row = $result->fetch_object()) {
                $types[] = $row;
            }
            return $types;
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM Blood_Type WHERE ID = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            $type = $res->fetch_object();
            $stmt->close();
            return $type;
        }
    }

    public function update($id, $data)
    {
        $stmt = $this->conn->prepare("UPDATE Blood_Type SET Type = ? WHERE ID = ?");
        $stmt->bind_param("si", $data['Type'], $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM Blood_Type WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}