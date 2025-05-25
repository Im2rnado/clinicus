<?php
// Model/entities/UserTypePages.php

class UserTypePages
{
    public $ID;
    public $userTypeID;
    public $pageID;
    public $orderValue;
    public $createdAt;
    public $updatedAt;
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($data)
    {
        $stmt = $this->conn->prepare("INSERT INTO User_Type_Pages (userTypeID, pageID, orderValue) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $data['userTypeID'], $data['pageID'], $data['orderValue']);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function read($id = null)
    {
        if ($id === null) {
            $result = $this->conn->query("SELECT * FROM User_Type_Pages");
            $pages = [];
            while ($row = $result->fetch_object()) {
                $pages[] = $row;
            }
            return $pages;
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM User_Type_Pages WHERE ID = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            $page = $res->fetch_object();
            $stmt->close();
            return $page;
        }
    }

    public function update($id, $data)
    {
        $stmt = $this->conn->prepare("UPDATE User_Type_Pages SET userTypeID = ?, pageID = ?, orderValue = ? WHERE ID = ?");
        $stmt->bind_param("iiii", $data['userTypeID'], $data['pageID'], $data['orderValue'], $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM User_Type_Pages WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}