<?php
// Model/entities/MessageUser.php
namespace Model\entities;

class MessageUser
{
    public $Id;
    public $messageID;
    public $userID;
    public $createdAt;
    public $updatedAt;
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($data)
    {
        $stmt = $this->conn->prepare("INSERT INTO Message_User (messageID, userID) VALUES (?, ?)");
        $stmt->bind_param("ii", $data['messageID'], $data['userID']);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function read($id = null)
    {
        if ($id === null) {
            $result = $this->conn->query("SELECT * FROM Message_User");
            $users = [];
            while ($row = $result->fetch_object()) {
                $users[] = $row;
            }
            return $users;
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM Message_User WHERE Id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            $user = $res->fetch_object();
            $stmt->close();
            return $user;
        }
    }

    public function update($id, $data)
    {
        $stmt = $this->conn->prepare("UPDATE Message_User SET messageID = ?, userID = ? WHERE Id = ?");
        $stmt->bind_param("iii", $data['messageID'], $data['userID'], $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM Message_User WHERE Id = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}