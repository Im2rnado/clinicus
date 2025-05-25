<?php
// Model/entities/Patient.php
namespace Model\entities;

require_once __DIR__ . '/../abstract/AbstractUser.php';
use Model\abstract\AbstractUser;
use Model\entities\User;

class Patient extends AbstractUser
{
    public $bloodType;

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
        // Create user first, then patient record
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
            $stmt = $this->conn->prepare("INSERT INTO Patients (userID, bloodType) VALUES (?, ?)");
            $stmt->bind_param("ii", $userId, $data['bloodType']);
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        }
        return false;
    }

    public function read($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM Patients WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $patient = $res->fetch_object();
        $stmt->close();
        return $patient;
    }

    public function readAll()
    {
        $stmt = $this->conn->prepare("SELECT * FROM Patients");
        $stmt->execute();
        $res = $stmt->get_result();
        $patients = [];
        while ($row = $res->fetch_assoc()) {
            $patients[] = $row;
        }
        $stmt->close();
        return $patients;
    }

    public function update($id, $data)
    {
        $stmt = $this->conn->prepare("UPDATE Patients SET bloodType = ? WHERE ID = ?");
        $stmt->bind_param("ii", $data['bloodType'], $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM Patients WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}