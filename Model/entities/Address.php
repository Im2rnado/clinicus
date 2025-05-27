<?php
// Model/entities/Address.php
namespace Model\entities;

class Address
{
    public $Id;
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
        $stmt = $this->conn->prepare("INSERT INTO Address (name) VALUES (?)");
        $stmt->bind_param("s", $data['name']);
        $result = $stmt->execute();

        if ($result) {
            return $this->conn->insert_id; // Return the actual ID of the new address
        }
        return false;
    }

    public function read($id = null)
    {
        if ($id === null) {
            $result = $this->conn->query("SELECT * FROM Address");
            $addresses = [];
            while ($row = $result->fetch_object()) {
                $addresses[] = $row;
            }
            return $addresses;
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM Address WHERE Id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            $address = $res->fetch_object();
            $stmt->close();
            return $address;
        }
    }

    public function update($id, $data)
    {
        $stmt = $this->conn->prepare("UPDATE Address SET name = ? WHERE Id = ?");
        $stmt->bind_param("si", $data['name'], $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM Address WHERE Id = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function getByAddress($address)
    {
        $stmt = $this->conn->prepare("SELECT * FROM Address WHERE name = ?");
        $stmt->bind_param("s", $address);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}