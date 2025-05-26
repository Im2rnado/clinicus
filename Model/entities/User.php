<?php
// Model/entities/User.php

namespace Model\entities;

use Model\abstract\AbstractUser;
use Model\interfaces\ILogUser;

class User extends AbstractUser implements ILogUser
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
        parent::__construct($db);
    }

    public function getUserById($userId)
    {
        return $this->read($userId);
    }

    public function createUser($userData)
    {
        return $this->create($userData);
    }

    public function updateUser($userId, $userData)
    {
        return $this->update($userId, $userData);
    }

    public function deleteUser($userId)
    {
        return $this->delete($userId);
    }

    // Implement abstract CRUD methods
    public function create($data)
    {
        $stmt = $this->conn->prepare("INSERT INTO Users (FirstName, LastName, username, password, dob, addressID, roleID) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssi", $data['FirstName'], $data['LastName'], $data['username'], $data['password'], $data['dob'], $data['addressID'], $data['roleID']);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function read($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM Users WHERE userID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $user = $res->fetch_object();
        $stmt->close();
        return $user;
    }

    public function readAll()
    {
        $stmt = $this->conn->prepare("SELECT * FROM Users");
        $stmt->execute();
        $res = $stmt->get_result();
        $users = [];
        while ($row = $res->fetch_assoc()) {
            $users[] = $row;
        }
        $stmt->close();
        return $users;
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

    // --- Functions from class diagram ---
    public function login($username, $password): bool
    {
        // TODO: Implement login logic
        return false;
    }

    public function logout(): void
    {
        // TODO: Implement logout logic
    }

    public function resetPassword($email): bool
    {
        // TODO: Implement password reset logic
        return false;
    }

    public function updateProfile($data): bool
    {
        // TODO: Implement profile update logic
        return false;
    }
}
