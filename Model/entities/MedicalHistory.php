<?php
// Model/entities/MedicalHistory.php

class MedicalHistory
{
    public $ID;
    public $patientID;
    public $serviceID;
    public $appointmentID;
    public $description;
    public $createdBy;
    public $createdAt;
    public $updatedAt;
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($data)
    {
        $stmt = $this->conn->prepare("INSERT INTO Medical_History (patientID, serviceID, appointmentID, description, createdBy) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiisi", $data['patientID'], $data['serviceID'], $data['appointmentID'], $data['description'], $data['createdBy']);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function read($id = null)
    {
        if ($id === null) {
            $result = $this->conn->query("SELECT * FROM Medical_History");
            $histories = [];
            while ($row = $result->fetch_object()) {
                $histories[] = $row;
            }
            return $histories;
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM Medical_History WHERE ID = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            $history = $res->fetch_object();
            $stmt->close();
            return $history;
        }
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
}