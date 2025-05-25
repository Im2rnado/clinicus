<?php
// Model/entities/Category.php

class Category
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
        $stmt = $this->conn->prepare("INSERT INTO Category (Name) VALUES (?)");
        $stmt->bind_param("s", $data['Name']);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function read($id = null)
    {
        if ($id === null) {
            $result = $this->conn->query("SELECT * FROM Category");
            $categories = [];
            while ($row = $result->fetch_object()) {
                $categories[] = $row;
            }
            return $categories;
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM Category WHERE ID = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            $category = $res->fetch_object();
            $stmt->close();
            return $category;
        }
    }

    public function update($id, $data)
    {
        $stmt = $this->conn->prepare("UPDATE Category SET Name = ? WHERE ID = ?");
        $stmt->bind_param("si", $data['Name'], $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM Category WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}