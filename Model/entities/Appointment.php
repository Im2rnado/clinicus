<?php
// Model/entities/Appointment.php
namespace Model\entities;
class Appointment
{
    public $ID;
    public $DoctorID;
    public $userID;
    public $appointmentDate;
    public $status;
    public $createdAt;
    public $updatedAt;
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($data)
    {
        $stmt = $this->conn->prepare("INSERT INTO Appointment (DoctorID, userID, appointmentDate, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iisi", $data['DoctorID'], $data['userID'], $data['appointmentDate'], $data['status']);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function read($id = null)
    {
        if ($id === null) {
            $result = $this->conn->query("SELECT * FROM Appointment");
            $appointments = [];
            while ($row = $result->fetch_object()) {
                $appointments[] = $row;
            }
            return $appointments;
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM Appointment WHERE ID = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            $appointment = $res->fetch_object();
            $stmt->close();
            return $appointment;
        }
    }

    public function readAll()
    {
        $stmt = $this->conn->prepare("SELECT * FROM Appointment");
        $stmt->execute();
        $res = $stmt->get_result();
        $appointments = [];
        while ($row = $res->fetch_assoc()) {
            $appointments[] = $row;
        }
        $stmt->close();
        return $appointments;
    }


    public function update($id, $data)
    {
        $stmt = $this->conn->prepare("UPDATE Appointment SET DoctorID = ?, userID = ?, appointmentDate = ?, status = ? WHERE ID = ?");
        $stmt->bind_param("iisii", $data['DoctorID'], $data['userID'], $data['appointmentDate'], $data['status'], $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM Appointment WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // --- Appointment class diagram methods ---
    public function schedule($doctorId, $userId, $date, $status = 'scheduled')
    {
        $stmt = $this->conn->prepare("INSERT INTO Appointment (DoctorID, userID, appointmentDate, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $doctorId, $userId, $date, $status);
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