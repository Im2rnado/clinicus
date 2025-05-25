<?php
// Model/entities/MedicationInfo.php
namespace Model\entities;

class MedicationInfo
{
    public $serviceID;
    public $name;
    public $brandName;
    public $dosageForm;
    public $strenght;
    public $unit;
    public $category;
    public $expireDuration;
    public $createdAt;
    public $updatedAt;
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($data)
    {
        $stmt = $this->conn->prepare("INSERT INTO Medication_Info (name, brandName, dosageForm, strenght, unit, category, expireDuration) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiiiii", $data['name'], $data['brandName'], $data['dosageForm'], $data['strenght'], $data['unit'], $data['category'], $data['expireDuration']);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function read($id = null)
    {
        if ($id === null) {
            $result = $this->conn->query("SELECT * FROM Medication_Info");
            $meds = [];
            while ($row = $result->fetch_object()) {
                $meds[] = $row;
            }
            return $meds;
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM Medication_Info WHERE serviceID = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            $med = $res->fetch_object();
            $stmt->close();
            return $med;
        }
    }

    public function update($id, $data)
    {
        $stmt = $this->conn->prepare("UPDATE Medication_Info SET name = ?, brandName = ?, dosageForm = ?, strenght = ?, unit = ?, category = ?, expireDuration = ? WHERE serviceID = ?");
        $stmt->bind_param("ssiiiiii", $data['name'], $data['brandName'], $data['dosageForm'], $data['strenght'], $data['unit'], $data['category'], $data['expireDuration'], $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM Medication_Info WHERE serviceID = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}