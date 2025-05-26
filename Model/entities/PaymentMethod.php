<?php
// Model/entities/PaymentMethod.php
namespace Model\entities;

use IPayment;

class PaymentMethod extends \AbstractPaymentMethod
{
    public $ID;
    public $name;
    public $createdAt;
    public $updatedAt;
    private $conn;

    public function __construct($db)
    {
        parent::__construct($db);
    }

    // Implement IPayment methods
    public function processPayment($paymentId)
    {
        $stmt = $this->conn->prepare("UPDATE Payment SET status = 'processed' WHERE ID = ?");
        $stmt->bind_param("i", $paymentId);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function validatePayment($data)
    {
        // Example: Validate payment data
        return isset($data['name']) && !empty($data['name']);
    }

    public function getPaymentDetails($id)
    {
        return $this->read($id);
    }

    // Implement CRUD logic
    public function create($data)
    {
        $stmt = $this->conn->prepare("INSERT INTO Payment_Methods (name) VALUES (?)");
        $stmt->bind_param("s", $data['name']);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function read($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM Payment_Methods WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $method = $res->fetch_object();
        $stmt->close();
        return $method;
    }

    public function update($id, $data)
    {
        $stmt = $this->conn->prepare("UPDATE Payment_Methods SET name = ? WHERE ID = ?");
        $stmt->bind_param("si", $data['name'], $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM Payment_Methods WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // --- Payment class diagram methods ---
    public function invoiceLog($paymentId)
    {
        $stmt = $this->conn->prepare("SELECT * FROM Invoice WHERE paymentID = ?");
        $stmt->bind_param("i", $paymentId);
        $stmt->execute();
        $res = $stmt->get_result();
        $invoices = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $invoices;
    }

    public function addAttribute($paymentId, $attribute, $value)
    {
        $stmt = $this->conn->prepare("UPDATE Payment SET $attribute = ? WHERE ID = ?");
        $stmt->bind_param("si", $value, $paymentId);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}