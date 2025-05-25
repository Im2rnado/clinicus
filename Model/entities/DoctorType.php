<?php
// Model/entities/DoctorType.php

class DoctorType
{
    public $ID;
    public $Specilization;
    public $createdAt;
    public $updatedAt;
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($data)
    {
        $stmt = $this->conn->prepare("INSERT INTO Doctor_Types (Specilization) VALUES (?)");
        $stmt->bind_param("s", $data['Specilization']);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function read($id = null)
    {
        if ($id === null) {
            $result = $this->conn->query("SELECT * FROM Doctor_Types");
            $types = [];
            while ($row = $result->fetch_object()) {
                $types[] = $row;
            }
            return $types;
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM Doctor_Types WHERE ID = ?");
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
        $stmt = $this->conn->prepare("UPDATE Doctor_Types SET Specilization = ? WHERE ID = ?");
        $stmt->bind_param("si", $data['Specilization'], $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM Doctor_Types WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}