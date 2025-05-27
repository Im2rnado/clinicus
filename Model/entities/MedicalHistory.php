<?php
// Model/entities/MedicalHistory.php
namespace Model\entities;

include_once __DIR__ . "/../interfaces/IHealthRecord.php";

use AbstractMedicalHistory;
use Model\interfaces\IHealthRecord;

class MedicalHistory extends AbstractMedicalHistory implements IHealthRecord
{
    public $ID;
    public $patientID;
    public $serviceID;
    public $appointmentID;
    public $description;
    public $createdBy;
    public $createdAt;
    public $updatedAt;
    protected $conn;

    public function __construct($db)
    {
        parent::__construct($db);
    }

    // Implement IHealthRecord methods
    public function addRecord($data)
    {
        return $this->create($data);
    }

    public function getRecord($id)
    {
        return $this->read($id);
    }

    public function getData()
    {
        return $this->getHistory();
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function updateRecord($id, $data)
    {
        return $this->update($id, $data);
    }

    // Implement CRUD logic
    public function create($data)
    {
        $stmt = $this->conn->prepare("INSERT INTO Medical_History (patientID, serviceID, appointmentID, description, createdBy) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiisi", $data['patientID'], $data['serviceID'], $data['appointmentID'], $data['description'], $data['createdBy']);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function read($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM Medical_History WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $history = $res->fetch_object();
        $stmt->close();
        return $history;
    }

    public function update($id, $data)
    {
        $stmt = $this->conn->prepare("UPDATE Medical_History SET patientID = ?, serviceID = ?, appointmentID = ?, description = ?, createdBy = ? WHERE ID = ?");
        $stmt->bind_param("iiisii", $data['patientID'], $data['serviceID'], $data['appointmentID'], $data['description'], $data['createdBy'], $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM Medical_History WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function deleteRecord($recordId)
    {
        $stmt = $this->conn->prepare("DELETE FROM Medical_History WHERE ID = ?");
        $stmt->bind_param("i", $recordId);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function getHistory()
    {
        $result = $this->conn->query("SELECT * FROM Medical_History");
        $histories = [];
        while ($row = $result->fetch_object()) {
            $histories[] = $row;
        }
        return $histories;
    }

    public function getRecentHistory($patientId)
    {
        $stmt = $this->conn->prepare("
            SELECT 
                mh.ID,
                mh.description,
                a.appointmentDate as date,
                CONCAT(d.FirstName, ' ', d.LastName) as doctorName
            FROM Medical_History mh
            JOIN Appointment a ON mh.appointmentID = a.ID
            JOIN Doctors doc ON a.DoctorID = doc.ID
            JOIN Users d ON doc.userID = d.userID
            WHERE mh.patientID = ?
            ORDER BY a.appointmentDate DESC
            LIMIT 5
        ");
        $stmt->bind_param("i", $patientId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getAllHistory($patientId)
    {
        $stmt = $this->conn->prepare("
            SELECT 
                mh.ID,
                mh.description,
                a.appointmentDate as date,
                CONCAT(d.FirstName, ' ', d.LastName) as doctorName
            FROM Medical_History mh
            JOIN Appointment a ON mh.appointmentID = a.ID
            JOIN Doctors doc ON a.DoctorID = doc.ID
            JOIN Users d ON doc.userID = d.userID
            WHERE mh.patientID = ?
            ORDER BY a.appointmentDate DESC
        ");
        $stmt->bind_param("i", $patientId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getPrescriptions($patientId)
    {
        $stmt = $this->conn->prepare("
            SELECT 
                p.ID,
                p.medication,
                p.dosage,
                p.frequency,
                p.duration,
                p.notes,
                p.date,
                p.status,
                CONCAT(d.FirstName, ' ', d.LastName) as doctorName
            FROM Prescriptions p
            JOIN Doctors doc ON p.DoctorID = doc.ID
            JOIN Users d ON doc.userID = d.userID
            WHERE p.userID = ?
            ORDER BY p.date DESC
        ");
        $stmt->bind_param("i", $patientId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function createPrescription($data)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO Prescriptions (userID, DoctorID, medication, dosage, frequency, duration, notes, date, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "iisssssss",
            $data['userID'],
            $data['doctorID'],
            $data['medication'],
            $data['dosage'],
            $data['frequency'],
            $data['duration'],
            $data['notes'],
            $data['date'],
            $data['status']
        );
        return $stmt->execute();
    }
}