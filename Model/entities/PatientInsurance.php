<?php
// Model/entities/PatientInsurance.php
namespace Model\entities;

class PatientInsurance
{
    public $ID;
    public $patientID;
    public $providerID;
    public $insuranceNO;
    public $createdAt;
    public $updatedAt;
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($data)
    {
        $stmt = $this->conn->prepare("INSERT INTO Patient_Insurance (patientID, providerID, insuranceNO) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $data['patientID'], $data['providerID'], $data['insuranceNO']);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function read($id = null)
    {
        if ($id === null) {
            $result = $this->conn->query("SELECT * FROM Patient_Insurance");
            $insurances = [];
            while ($row = $result->fetch_object()) {
                $insurances[] = $row;
            }
            return $insurances;
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM Patient_Insurance WHERE ID = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            $insurance = $res->fetch_object();
            $stmt->close();
            return $insurance;
        }
    }

    public function update($id, $data)
    {
        $stmt = $this->conn->prepare("UPDATE Patient_Insurance SET patientID = ?, providerID = ?, insuranceNO = ? WHERE ID = ?");
        $stmt->bind_param("iisi", $data['patientID'], $data['providerID'], $data['insuranceNO'], $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM Patient_Insurance WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}