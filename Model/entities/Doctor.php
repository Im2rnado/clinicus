<?php
// Model/entities/Doctor.php
namespace Model\entities;

require_once __DIR__ . '/../abstract/AbstractModel.php';

use Model\abstract\AbstractModel;
use Model\entities\User;

class Doctor extends AbstractModel
{
    public $ID;
    public $userID;
    public $doctorType;
    public $yearsOfExperince;
    public $rating;
    public $consultation_fee;
    public $createdAt;
    public $updatedAt;

    protected $conn;

    public function __construct($db)
    {
        parent::__construct($db);
        $this->tableName = 'Doctors';
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
        try {
            $sql = "INSERT INTO {$this->tableName} (userID, doctorType, yearsOfExperince, rating, consultation_fee, createdAt, updatedAt) 
                    VALUES (?, ?, ?, ?, ?, NOW(), NOW())";
            $stmt = $this->conn->prepare($sql);

            return $stmt->execute([
                $data['userID'],
                $data['doctorType'],
                $data['yearsOfExperince'],
                $data['rating'] ?? 0,
                $data['consultation_fee'] ?? 0
            ]);
        } catch (\Exception $e) {
            error_log('Exception in Doctor::create: ' . $e->getMessage());
            return false;
        }
    }

    public function read($id)
    {
        $stmt = $this->conn->prepare("
            SELECT d.*, 
                   CONCAT(u.FirstName, ' ', u.LastName) as doctorName,
                   dt.Specialization as specialization
            FROM {$this->tableName} d
            JOIN Users u ON d.userID = u.userID
            JOIN doctor_types dt ON d.doctorType = dt.ID
            WHERE d.ID = ?
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $doctor = $result->fetch_assoc();
        $stmt->close();
        return $doctor;
    }

    public function readAll()
    {
        $stmt = $this->conn->prepare("
            SELECT d.*, 
                   u.FirstName,
                   u.LastName,
                   dt.Specialization as specialization
            FROM {$this->tableName} d
            JOIN Users u ON d.userID = u.userID
            JOIN doctor_types dt ON d.doctorType = dt.ID
            ORDER BY d.ID DESC
        ");
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
        try {
            $sql = "UPDATE {$this->tableName} SET 
                    doctorType = ?, 
                    yearsOfExperince = ?, 
                    rating = ?, 
                    consultation_fee = ?, 
                    updatedAt = NOW() 
                    WHERE ID = ?";
            $stmt = $this->conn->prepare($sql);

            return $stmt->execute([
                $data['doctorType'],
                $data['yearsOfExperince'],
                $data['rating'],
                $data['consultation_fee'],
                $id
            ]);
        } catch (\Exception $e) {
            error_log('Exception in Doctor::update: ' . $e->getMessage());
            return false;
        }
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->tableName} WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function getAll()
    {
        $query = "SELECT d.*, u.FirstName, u.LastName, u.email, u.phone, a.name as address 
                 FROM Doctors d 
                 JOIN Users u ON d.userID = u.userID 
                 JOIN Address a ON u.addressID = a.ID 
                 ORDER BY u.FirstName, u.LastName";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
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

    public function getAllSpecializations()
    {
        $query = "SELECT ID, Specialization FROM doctor_types ORDER BY Specialization";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getDoctorsBySpecialization($specializationId)
    {
        $stmt = $this->conn->prepare("
            SELECT d.*, 
                   CONCAT(u.FirstName, ' ', u.LastName) as doctorName,
                   dt.Specialization as specialization
            FROM {$this->tableName} d
            JOIN Users u ON d.userID = u.userID
            JOIN doctor_types dt ON d.doctorType = dt.ID
            WHERE d.doctorType = ?
        ");
        $stmt->bind_param("i", $specializationId);
        $stmt->execute();
        $result = $stmt->get_result();
        $doctors = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $doctors;
    }

    public function updateRating($id, $newRating)
    {
        try {
            $sql = "UPDATE {$this->tableName} SET rating = ?, updatedAt = NOW() WHERE ID = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("di", $newRating, $id);
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        } catch (\Exception $e) {
            error_log('Exception in Doctor::updateRating: ' . $e->getMessage());
            return false;
        }
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

    public function getTotalPatients($doctorId)
    {
        $stmt = $this->conn->prepare("
            SELECT COUNT(DISTINCT a.userID) as total
            FROM Appointment a
            WHERE a.DoctorID = ?
        ");
        $stmt->bind_param("i", $doctorId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['total'];
    }
}