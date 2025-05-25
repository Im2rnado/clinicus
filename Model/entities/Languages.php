<?php
// Model/entities/Languages.php

class Languages
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
        $stmt = $this->conn->prepare("INSERT INTO Languages (name) VALUES (?)");
        $stmt->bind_param("s", $data['name']);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function read($id = null)
    {
        if ($id === null) {
            $result = $this->conn->query("SELECT * FROM Languages");
            $languages = [];
            while ($row = $result->fetch_object()) {
                $languages[] = $row;
            }
            return $languages;
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM Languages WHERE ID = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            $language = $res->fetch_object();
            $stmt->close();
            return $language;
        }
    }

    public function update($id, $data)
    {
        $stmt = $this->conn->prepare("UPDATE Languages SET name = ? WHERE ID = ?");
        $stmt->bind_param("si", $data['name'], $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM Languages WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}