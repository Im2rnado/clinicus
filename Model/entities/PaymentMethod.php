<?php
// Model/entities/PaymentMethod.php

class PaymentMethod
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
        $stmt = $this->conn->prepare("INSERT INTO Payment_Methods (name) VALUES (?)");
        $stmt->bind_param("s", $data['name']);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function read($id = null)
    {
        if ($id === null) {
            $result = $this->conn->query("SELECT * FROM Payment_Methods");
            $methods = [];
            while ($row = $result->fetch_object()) {
                $methods[] = $row;
            }
            return $methods;
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM Payment_Methods WHERE ID = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            $method = $res->fetch_object();
            $stmt->close();
            return $method;
        }
    }

    public function update($id, $data)
    {
        $stmt = $this->conn->prepare("UPDATE Payment_Methods SET name = ? WHERE ID = ?");
        $stmt->bind_param("si", $data['name'], $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM Payment_Methods WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}