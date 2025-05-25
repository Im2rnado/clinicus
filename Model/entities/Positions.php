<?php
// Model/entities/Positions.php

class Positions
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
        $stmt = $this->conn->prepare("INSERT INTO Positions (name) VALUES (?)");
        $stmt->bind_param("s", $data['name']);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function read($id = null)
    {
        if ($id === null) {
            $result = $this->conn->query("SELECT * FROM Positions");
            $positions = [];
            while ($row = $result->fetch_object()) {
                $positions[] = $row;
            }
            return $positions;
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM Positions WHERE ID = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            $position = $res->fetch_object();
            $stmt->close();
            return $position;
        }
    }

    public function update($id, $data)
    {
        $stmt = $this->conn->prepare("UPDATE Positions SET name = ? WHERE ID = ?");
        $stmt->bind_param("si", $data['name'], $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM Positions WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}