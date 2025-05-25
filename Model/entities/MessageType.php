<?php
// Model/entities/MessageType.php
namespace Model\entities;

class MessageType
{
    public $Id;
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
        $stmt = $this->conn->prepare("INSERT INTO Message_Type (Type) VALUES (?)");
        $stmt->bind_param("s", $data['Type']);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function read($id = null)
    {
        if ($id === null) {
            $result = $this->conn->query("SELECT * FROM Message_Type");
            $types = [];
            while ($row = $result->fetch_object()) {
                $types[] = $row;
            }
            return $types;
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM Message_Type WHERE Id = ?");
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
        $stmt = $this->conn->prepare("UPDATE Message_Type SET Type = ? WHERE Id = ?");
        $stmt->bind_param("si", $data['Type'], $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM Message_Type WHERE Id = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}