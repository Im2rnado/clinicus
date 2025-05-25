<?php
// Model/entities/User.php

class User
{
    public $userID;
    public $FirstName;
    public $LastName;
    public $username;
    public $password;
    public $dob;
    public $addressID;
    public $roleID;
    public $createdAt;
    public $updatedAt;

    protected $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($data)
    {
        $stmt = $this->conn->prepare("INSERT INTO Users (FirstName, LastName, username, password, dob, addressID, roleID) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssi", $data['FirstName'], $data['LastName'], $data['username'], $data['password'], $data['dob'], $data['addressID'], $data['roleID']);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function read($id = null)
    {
        if ($id === null) {
            $result = $this->conn->query("SELECT * FROM Users");
            $users = [];
            while ($row = $result->fetch_object()) {
                $users[] = $row;
            }
            return $users;
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM Users WHERE userID = ?");
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
        $stmt = $this->conn->prepare("UPDATE Users SET FirstName = ?, LastName = ?, username = ?, password = ?, dob = ?, addressID = ?, roleID = ? WHERE userID = ?");
        $stmt->bind_param("ssssssii", $data['FirstName'], $data['LastName'], $data['username'], $data['password'], $data['dob'], $data['addressID'], $data['roleID'], $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM Users WHERE userID = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}
