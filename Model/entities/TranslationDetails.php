<?php
// Model/entities/TranslationDetails.php

class TranslationDetails
{
    public $ID;
    public $TransID;
    public $pageID;
    public $wordID;
    public $TransWordID;
    public $createdAt;
    public $updatedAt;
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($data)
    {
        $stmt = $this->conn->prepare("INSERT INTO Translation_Details (TransID, pageID, wordID, TransWordID) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiii", $data['TransID'], $data['pageID'], $data['wordID'], $data['TransWordID']);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function read($id = null)
    {
        if ($id === null) {
            $result = $this->conn->query("SELECT * FROM Translation_Details");
            $details = [];
            while ($row = $result->fetch_object()) {
                $details[] = $row;
            }
            return $details;
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM Translation_Details WHERE ID = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            $detail = $res->fetch_object();
            $stmt->close();
            return $detail;
        }
    }

    public function update($id, $data)
    {
        $stmt = $this->conn->prepare("UPDATE Translation_Details SET TransID = ?, pageID = ?, wordID = ?, TransWordID = ? WHERE ID = ?");
        $stmt->bind_param("iiiii", $data['TransID'], $data['pageID'], $data['wordID'], $data['TransWordID'], $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM Translation_Details WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}