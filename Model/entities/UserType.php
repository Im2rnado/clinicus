<?php
// Model/entities/UserType.php
namespace Model\entities;

class UserType
{
    public $userTypeID;
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
        $stmt = $this->conn->prepare("INSERT INTO User_Types (Type) VALUES (?)");
        $stmt->bind_param("s", $data['Type']);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function read($id = null)
    {
        if ($id === null) {
            $result = $this->conn->query("SELECT * FROM User_Types");
            $types = [];
            while ($row = $result->fetch_object()) {
                $types[] = $row;
            }
            return $types;
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM User_Types WHERE userTypeID = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            $type = $res->fetch_object();
            $stmt->close();
            return $type;
        }
    }

    public function readAll()
    {
        $stmt = $this->conn->prepare("SELECT * FROM User_Types");
        $stmt->execute();
        $res = $stmt->get_result();
        $types = [];
        while ($row = $res->fetch_assoc()) {
            $types[] = $row;
        }
        $stmt->close();
        return $types;
    }

    public function update($id, $data)
    {
        $stmt = $this->conn->prepare("UPDATE User_Types SET Type = ? WHERE userTypeID = ?");
        $stmt->bind_param("si", $data['Type'], $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM User_Types WHERE userTypeID = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}