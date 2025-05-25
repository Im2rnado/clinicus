<?php
// Model/entities/Appointment.php

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
}