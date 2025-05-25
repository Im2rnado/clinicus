<?php
// Model/entities/Staff.php
namespace Model\entities;

require_once __DIR__ . '/../abstract/AbstractUser.php';
use Model\abstract\AbstractUser;
use Model\entities\User;

class Staff extends AbstractUser
{
    public $ID;
    public $userID;
    public $hiredAt;
    public $departmentID;
    public $positionID;
    public $createdAt;
    public $updatedAt;

    protected $conn;

    public function __construct($db)
    {
        parent::__construct($db);
        $this->conn = $db;
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

    public function create($data)
    {
        // Create user first, then staff record
        $userCreated = false;
        if (isset($data['user'])) {
            $user = new User($this->conn);
            $userCreated = $user->create($data['user']);
            $userId = $this->conn->insert_id;
        } else {
            $userId = $data['userID'];
            $userCreated = true;
        }
        if ($userCreated) {
            $stmt = $this->conn->prepare("INSERT INTO Staff (userID, hiredAt, departmentID, positionID) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isii", $userId, $data['hiredAt'], $data['departmentID'], $data['positionID']);
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        }
        return false;
    }

    public function read($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM Staff WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $staff = $res->fetch_object();
        $stmt->close();
        return $staff;
    }

    public function readAll()
    {
        $stmt = $this->conn->prepare("SELECT * FROM Staff");
        $stmt->execute();
        $res = $stmt->get_result();
        $staff = [];
        while ($row = $res->fetch_assoc()) {
            $staff[] = $row;
        }
        $stmt->close();
        return $staff;
    }

    public function update($id, $data)
    {
        $stmt = $this->conn->prepare("UPDATE Staff SET hiredAt = ?, departmentID = ?, positionID = ? WHERE ID = ?");
        $stmt->bind_param("siii", $data['hiredAt'], $data['departmentID'], $data['positionID'], $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM Staff WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}