<?php
// Model/entities/User.php

namespace Model\entities;

require_once __DIR__ . "/../abstract/AbstractModel.php";

use Model\abstract\AbstractModel;

class User extends AbstractModel
{
    public $userID;
    public $FirstName;
    public $LastName;
    public $username;
    public $email;
    public $phone;
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
        $this->tableName = 'Users';
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
        try {
            $sql = "INSERT INTO {$this->tableName} (FirstName, LastName, username, email, phone, password, dob, addressID, roleID, createdAt, updatedAt) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
            $stmt = $this->conn->prepare($sql);

            // Hash the password
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

            return $stmt->execute([
                $data['FirstName'],
                $data['LastName'],
                $data['username'],
                $data['email'],
                $data['phone'],
                $hashedPassword,
                $data['dob'],
                $data['addressID'],
                $data['roleID']
            ]);
        } catch (\Exception $e) {
            error_log('Exception in User::create: ' . $e->getMessage());
            return false;
        }
    }

    public function read($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->tableName} WHERE userID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    }

    public function readAll()
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->tableName}");
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
        try {
            $sql = "UPDATE {$this->tableName} SET 
                    FirstName = ?, 
                    LastName = ?, 
                    username = ?, 
                    email = ?, 
                    phone = ?, 
                    dob = ?, 
                    addressID = ?, 
                    roleID = ?, 
                    updatedAt = NOW() 
                    WHERE userID = ?";
            $stmt = $this->conn->prepare($sql);

            return $stmt->execute([
                $data['FirstName'],
                $data['LastName'],
                $data['username'],
                $data['email'],
                $data['phone'],
                $data['dob'],
                $data['addressID'],
                $data['roleID'],
                $id
            ]);
        } catch (\Exception $e) {
            error_log('Exception in User::update: ' . $e->getMessage());
            return false;
        }
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->tableName} WHERE userID = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // --- User class diagram methods with full logic ---
    public function login($username, $password)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->tableName} WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $res = $stmt->get_result();
        $user = $res->fetch_assoc();
        $stmt->close();
        if ($user && password_verify($password, $user['password'])) {
            // Set session or token logic here
            $_SESSION['userID'] = $user['userID'];
            return true;
        }
        return false;
    }

    public function logout()
    {
        // Destroy session or token logic
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        return true;
    }

    public function updateProfile($userId, $data)
    {
        $fields = [];
        $params = [];
        $types = '';
        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $params[] = $value;
            $types .= 's';
        }
        $params[] = $userId;
        $types .= 'i';
        $sql = "UPDATE {$this->tableName} SET " . implode(", ", $fields) . " WHERE userID = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function deleteAccount($userId)
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->tableName} WHERE userID = ?");
        $stmt->bind_param("i", $userId);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function changePassword($userId, $oldPassword, $newPassword)
    {
        // Verify old password
        $stmt = $this->conn->prepare("SELECT password FROM {$this->tableName} WHERE userID = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $res = $stmt->get_result();
        $user = $res->fetch_assoc();
        $stmt->close();
        if (!$user || !password_verify($oldPassword, $user['password'])) {
            return false;
        }
        // Update to new password
        $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("UPDATE {$this->tableName} SET password = ? WHERE userID = ?");
        $stmt->bind_param("si", $newPasswordHash, $userId);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function resetPassword($email): bool
    {
        // TODO: Implement password reset logic
        return false;
    }

    public function getByUsername($username)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->tableName} WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    }

    public function getByEmail($email)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->tableName} WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    }

    public function getCount()
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM {$this->tableName}");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['count'];
    }

    public function findByEmail($email)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->tableName} WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    }

    public function findByUsername($username)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->tableName} WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    }

    public function updatePassword($id, $newPassword)
    {
        try {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare("UPDATE {$this->tableName} SET password = ?, updatedAt = NOW() WHERE userID = ?");
            $stmt->bind_param("si", $hashedPassword, $id);
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        } catch (\Exception $e) {
            error_log('Exception in User::updatePassword: ' . $e->getMessage());
            return false;
        }
    }

    public function verifyPassword($password, $hashedPassword)
    {
        return password_verify($password, $hashedPassword);
    }

}
