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

    // --- Patient class diagram methods ---
    public function requestMedicalHistory($patientId)
    {
        $stmt = $this->conn->prepare("SELECT * FROM MedicalHistory WHERE patientID = ?");
        $stmt->bind_param("i", $patientId);
        $stmt->execute();
        $res = $stmt->get_result();
        $history = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $history;
    }

    public function requestFollowUp($appointmentId, $notes = null)
    {
        $stmt = $this->conn->prepare("UPDATE Appointment SET followUpRequested = 1, followUpNotes = ? WHERE ID = ?");
        $stmt->bind_param("si", $notes, $appointmentId);
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

    public function cancel($appointmentId)
    {
        $stmt = $this->conn->prepare("UPDATE Appointment SET status = 'cancelled' WHERE ID = ?");
        $stmt->bind_param("i", $appointmentId);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function schedule($doctorId, $userId, $date, $status = 'scheduled')
    {
        $stmt = $this->conn->prepare("INSERT INTO Appointment (DoctorID, userID, appointmentDate, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $doctorId, $userId, $date, $status);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function updateContactInfo($patientId, $contactData)
    {
        $fields = [];
        $params = [];
        $types = '';
        foreach ($contactData as $key => $value) {
            $fields[] = "$key = ?";
            $params[] = $value;
            $types .= 's';
        }
        $params[] = $patientId;
        $types .= 'i';
        $sql = "UPDATE Patients SET " . implode(", ", $fields) . " WHERE ID = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function rateDoctor($doctorId, $rating)
    {
        $stmt = $this->conn->prepare("UPDATE Doctors SET rating = ? WHERE ID = ?");
        $stmt->bind_param("ii", $rating, $doctorId);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function requestPrescription($patientId, $doctorId, $details)
    {
        $stmt = $this->conn->prepare("INSERT INTO Prescription (patientID, doctorID, details) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $patientId, $doctorId, $details);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function makePayment($patientId, $amount, $method)
    {
        $stmt = $this->conn->prepare("INSERT INTO Payment (patientID, amount, method) VALUES (?, ?, ?)");
        $stmt->bind_param("ids", $patientId, $amount, $method);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function payForAppointment($appointmentId, $amount, $method)
    {
        $stmt = $this->conn->prepare("INSERT INTO Payment (appointmentID, amount, method) VALUES (?, ?, ?)");
        $stmt->bind_param("ids", $appointmentId, $amount, $method);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function getCount()
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM Patients");
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_assoc()['COUNT(*)'];
    }
}