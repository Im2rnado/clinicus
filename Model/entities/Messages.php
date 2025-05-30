<?php
// Model/entities/Messages.php
namespace Model\entities;

use IMessageSender;

class Messages implements IMessageSender
{
    public $Id;
    public $messageTemplate;
    public $typeID;
    public $createdAt;
    public $updatedAt;
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Implement IMessageSender methods
    public function sendMessage($recipient, $message)
    {
        // Example: Insert message into Messages table
        $stmt = $this->conn->prepare("INSERT INTO Messages (messageTemplate, typeID) VALUES (?, ?)");
        $stmt->bind_param("si", $message, $recipient); // Here, recipient is typeID for demo
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function getMessageStatus($messageId)
    {
        $stmt = $this->conn->prepare("SELECT * FROM Messages WHERE Id = ?");
        $stmt->bind_param("i", $messageId);
        $stmt->execute();
        $res = $stmt->get_result();
        $message = $res->fetch_object();
        $stmt->close();
        return $message;
    }

    public function getMessagesByUserId($userId)
    {
        $stmt = $this->conn->prepare("SELECT * FROM Messages WHERE typeID = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    public function markAsRead($messageIds)
    {
        $stmt = $this->conn->prepare("UPDATE Messages SET isRead = 1 WHERE ID IN (" . implode(',', $messageIds) . ")");
        return $stmt->execute();
    }

    // Basic CRUD logic for Messages
    public function create($data)
    {
        $stmt = $this->conn->prepare("INSERT INTO Messages (messageTemplate, typeID) VALUES (?, ?)");
        $stmt->bind_param("si", $data['messageTemplate'], $data['typeID']);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function read($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM Messages WHERE Id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $message = $res->fetch_object();
        $stmt->close();
        return $message;
    }

    public function update($id, $data)
    {
        $stmt = $this->conn->prepare("UPDATE Messages SET messageTemplate = ?, typeID = ? WHERE Id = ?");
        $stmt->bind_param("sii", $data['messageTemplate'], $data['typeID'], $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM Messages WHERE Id = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}