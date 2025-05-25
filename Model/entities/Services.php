<?php
// Model/entities/Services.php

class Services
{
    public $ID;
    public $name;
    public $category;
    public $price;
    public $active;
    public $createdAt;
    public $updatedAt;
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($data)
    {
        $stmt = $this->conn->prepare("INSERT INTO Services (name, category, price, active) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sidi", $data['name'], $data['category'], $data['price'], $data['active']);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function read($id = null)
    {
        if ($id === null) {
            $result = $this->conn->query("SELECT * FROM Services");
            $services = [];
            while ($row = $result->fetch_object()) {
                $services[] = $row;
            }
            return $services;
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM Services WHERE ID = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            $service = $res->fetch_object();
            $stmt->close();
            return $service;
        }
    }

    public function update($id, $data)
    {
        $stmt = $this->conn->prepare("UPDATE Services SET name = ?, category = ?, price = ?, active = ? WHERE ID = ?");
        $stmt->bind_param("sidii", $data['name'], $data['category'], $data['price'], $data['active'], $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM Services WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}