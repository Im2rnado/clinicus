<?php
// Model/entities/Doctor.php

require_once 'User.php';

class Doctor extends User
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

    public function create($data)
    {
        $stmt = $this->conn->prepare("INSERT INTO Doctors (userID, yearsOfExperince, rating, docotrType) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiii", $data['userID'], $data['yearsOfExperince'], $data['rating'], $data['docotrType']);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function read($id = null)
    {
        if ($id === null) {
            $result = $this->conn->query("SELECT * FROM Doctors");
            $doctors = [];
            while ($row = $result->fetch_object()) {
                $doctors[] = $row;
            }
            return $doctors;
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM Doctors WHERE ID = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            $doctor = $res->fetch_object();
            $stmt->close();
            return $doctor;
        }
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
}