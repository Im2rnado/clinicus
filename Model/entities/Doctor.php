<?php
// Model/entities/Doctor.php
namespace Model\entities;

require_once __DIR__ . '/../abstract/AbstractUser.php';
use Model\abstract\AbstractUser;
use Model\entities\User;

class Doctor extends AbstractUser
{
    public $ID;
    public $yearsOfExperince;
    public $rating;
    public $docotrType;
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
        // Create user first, then doctor record
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
            $stmt = $this->conn->prepare("INSERT INTO Doctors (userID, yearsOfExperince, rating, docotrType) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiii", $userId, $data['yearsOfExperince'], $data['rating'], $data['docotrType']);
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        }
        return false;
    }

    public function read($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM Doctors WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $doctor = $res->fetch_object();
        $stmt->close();
        return $doctor;
    }

    public function readAll()
    {
        $stmt = $this->conn->prepare("SELECT * FROM Doctors");
        $stmt->execute();
        $res = $stmt->get_result();
        $doctors = [];
        while ($row = $res->fetch_assoc()) {
            $doctors[] = $row;
        }
        $stmt->close();
        return $doctors;
    }

    public function update($id, $data)
    {
        $stmt = $this->conn->prepare("UPDATE Doctors SET yearsOfExperince = ?, rating = ?, docotrType = ? WHERE ID = ?");
        $stmt->bind_param("iiii", $data['yearsOfExperince'], $data['rating'], $data['docotrType'], $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM Doctors WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // --- Doctor class diagram methods ---
    public function createPrescription($patientId, $details)
    {
        $stmt = $this->conn->prepare("INSERT INTO Prescription (doctorID, patientID, details) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $this->ID, $patientId, $details);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function schedule($patientId, $date, $status = 'scheduled')
    {
        $stmt = $this->conn->prepare("INSERT INTO Appointment (DoctorID, userID, appointmentDate, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $this->ID, $patientId, $date, $status);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function cancel($appointmentId)
    {
        $stmt = $this->conn->prepare("UPDATE Appointment SET status = 'cancelled' WHERE ID = ?");
        $stmt->bind_param("i", $appointmentId);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function reschedule($appointmentId, $newDate)
    {
        $stmt = $this->conn->prepare("UPDATE Appointment SET appointmentDate = ? WHERE ID = ?");
        $stmt->bind_param("si", $newDate, $appointmentId);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function requestFollowUp($appointmentId, $notes = null)
    {
        $stmt = $this->conn->prepare("UPDATE Appointment SET followUpRequested = 1, followUpNotes = ? WHERE ID = ?");
        $stmt->bind_param("si", $notes, $appointmentId);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}