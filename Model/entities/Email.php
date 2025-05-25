<?php
// Model/entities/Email.php
namespace Model\entities;

class Email
{
    public $Id;
    public $userID;
    public $email;
    public $createdAt;
    public $updatedAt;
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($data)
    {
        $stmt = $this->conn->prepare("INSERT INTO Email (userID, email) VALUES (?, ?)");
        $stmt->bind_param("is", $data['userID'], $data['email']);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function read($id = null)
    {
        if ($id === null) {
            $result = $this->conn->query("SELECT * FROM Email");
            $emails = [];
            while ($row = $result->fetch_object()) {
                $emails[] = $row;
            }
            return $emails;
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM Email WHERE Id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            $email = $res->fetch_object();
            $stmt->close();
            return $email;
        }
    }

    public function update($id, $data)
    {
        $stmt = $this->conn->prepare("UPDATE Email SET userID = ?, email = ? WHERE Id = ?");
        $stmt->bind_param("isi", $data['userID'], $data['email'], $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM Email WHERE Id = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}