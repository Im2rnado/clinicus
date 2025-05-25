<?php
// Model/entities/Patient.php

require_once 'User.php';

class Patient extends User
{
    public $ID;
    public $userID;
    public $bloodType;
    public $createdAt;
    public $updatedAt;

    protected $conn;

    public function __construct($db)
    {
        parent::__construct($db);
        $this->conn = $db;
    }

    public function create($data)
    {
        $stmt = $this->conn->prepare("INSERT INTO Patients (userID, bloodType) VALUES (?, ?)");
        $stmt->bind_param("ii", $data['userID'], $data['bloodType']);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function read($id = null)
    {
        if ($id === null) {
            $result = $this->conn->query("SELECT * FROM Patients");
            $patients = [];
            while ($row = $result->fetch_object()) {
                $patients[] = $row;
            }
            return $patients;
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM Patients WHERE ID = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            $patient = $res->fetch_object();
            $stmt->close();
            return $patient;
        }
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