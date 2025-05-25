<?php
// Model/entities/Translation.php

class Translation
{
    public $ID;
    public $firstLangCode;
    public $secondLangCode;
    public $createdAt;
    public $updatedAt;
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($data)
    {
        $stmt = $this->conn->prepare("INSERT INTO Translation (firstLangCode, secondLangCode) VALUES (?, ?)");
        $stmt->bind_param("ii", $data['firstLangCode'], $data['secondLangCode']);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function read($id = null)
    {
        if ($id === null) {
            $result = $this->conn->query("SELECT * FROM Translation");
            $translations = [];
            while ($row = $result->fetch_object()) {
                $translations[] = $row;
            }
            return $translations;
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM Translation WHERE ID = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            $translation = $res->fetch_object();
            $stmt->close();
            return $translation;
        }
    }

    public function update($id, $data)
    {
        $stmt = $this->conn->prepare("UPDATE Translation SET firstLangCode = ?, secondLangCode = ? WHERE ID = ?");
        $stmt->bind_param("iii", $data['firstLangCode'], $data['secondLangCode'], $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM Translation WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}