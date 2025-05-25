<?php
// Model/entities/PaymentOption.php
namespace Model\entities;

class PaymentOption
{
    public $ID;
    public $name;
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
        $stmt = $this->conn->prepare("INSERT INTO Options (name, Type) VALUES (?, ?)");
        $stmt->bind_param("ss", $data['name'], $data['Type']);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function read($id = null)
    {
        if ($id === null) {
            $result = $this->conn->query("SELECT * FROM Options");
            $options = [];
            while ($row = $result->fetch_object()) {
                $options[] = $row;
            }
            return $options;
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM Options WHERE ID = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            $option = $res->fetch_object();
            $stmt->close();
            return $option;
        }
    }

    public function update($id, $data)
    {
        $stmt = $this->conn->prepare("UPDATE Options SET name = ?, Type = ? WHERE ID = ?");
        $stmt->bind_param("ssi", $data['name'], $data['Type'], $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM Options WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}