<?php
// Model/entities/Address.php
namespace Model\entities;

class Address
{
    public $Id;
    public $name;
    public $reference;
    public $createdAt;
    public $updatedAt;
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($data)
    {
        $stmt = $this->conn->prepare("INSERT INTO Address (name, reference) VALUES (?, ?)");
        $stmt->bind_param("si", $data['name'], $data['reference']);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
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
        $stmt = $this->conn->prepare("UPDATE Address SET name = ?, reference = ? WHERE Id = ?");
        $stmt->bind_param("sii", $data['name'], $data['reference'], $id);
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
}