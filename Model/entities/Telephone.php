<?php
// Model/entities/Telephone.php
namespace Model\entities;

class Telephone
{
    public $Id;
    public $userID;
    public $telephone;
    public $createdAt;
    public $updatedAt;
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($data)
    {
        $stmt = $this->conn->prepare("INSERT INTO Telephone (userID, telephone) VALUES (?, ?)");
        $stmt->bind_param("is", $data['userID'], $data['telephone']);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function read($id = null)
    {
        if ($id === null) {
            $result = $this->conn->query("SELECT * FROM Telephone");
            $phones = [];
            while ($row = $result->fetch_object()) {
                $phones[] = $row;
            }
            return $phones;
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM Telephone WHERE Id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            $phone = $res->fetch_object();
            $stmt->close();
            return $phone;
        }
    }

    public function update($id, $data)
    {
        $stmt = $this->conn->prepare("UPDATE Telephone SET userID = ?, telephone = ? WHERE Id = ?");
        $stmt->bind_param("isi", $data['userID'], $data['telephone'], $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM Telephone WHERE Id = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}