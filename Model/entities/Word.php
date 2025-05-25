<?php
// Model/entities/Word.php

class Word
{
    public $ID;
    public $word;
    public $languageID;
    public $createdAt;
    public $updatedAt;
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($data)
    {
        $stmt = $this->conn->prepare("INSERT INTO Word (word, languageID) VALUES (?, ?)");
        $stmt->bind_param("si", $data['word'], $data['languageID']);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function read($id = null)
    {
        if ($id === null) {
            $result = $this->conn->query("SELECT * FROM Word");
            $words = [];
            while ($row = $result->fetch_object()) {
                $words[] = $row;
            }
            return $words;
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM Word WHERE ID = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            $word = $res->fetch_object();
            $stmt->close();
            return $word;
        }
    }

    public function update($id, $data)
    {
        $stmt = $this->conn->prepare("UPDATE Word SET word = ?, languageID = ? WHERE ID = ?");
        $stmt->bind_param("sii", $data['word'], $data['languageID'], $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM Word WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}