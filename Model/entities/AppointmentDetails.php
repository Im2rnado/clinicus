<?php
// Model/entities/AppointmentDetails.php
namespace Model\entities;
class AppointmentDetails
{
    public $ID;
    public $appointmentID;
    public $serviceID;
    public $quantity;
    public $pricePerUnit;
    public $totalPrice;
    public $createdAt;
    public $updatedAt;
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($data)
    {
        $stmt = $this->conn->prepare("INSERT INTO Appointment_Details (appointmentID, serviceID, quantity, pricePerUnit, totalPrice) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiidd", $data['appointmentID'], $data['serviceID'], $data['quantity'], $data['pricePerUnit'], $data['totalPrice']);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function read($id = null)
    {
        if ($id === null) {
            $result = $this->conn->query("SELECT * FROM Appointment_Details");
            $details = [];
            while ($row = $result->fetch_object()) {
                $details[] = $row;
            }
            return $details;
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM Appointment_Details WHERE ID = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            $detail = $res->fetch_object();
            $stmt->close();
            return $detail;
        }
    }

    public function update($id, $data)
    {
        $stmt = $this->conn->prepare("UPDATE Appointment_Details SET appointmentID = ?, serviceID = ?, quantity = ?, pricePerUnit = ?, totalPrice = ? WHERE ID = ?");
        $stmt->bind_param("iiiddi", $data['appointmentID'], $data['serviceID'], $data['quantity'], $data['pricePerUnit'], $data['totalPrice'], $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM Appointment_Details WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}