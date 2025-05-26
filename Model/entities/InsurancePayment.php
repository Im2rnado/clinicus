<?php
namespace Model\entities;

use IPayment;

class InsurancePayment implements IPayment
{
    private $conn;
    public function __construct($db)
    {
        $this->conn = $db;
    }
    public function executePayment($paymentId)
    {
        $stmt = $this->conn->prepare("UPDATE Payment SET status = 'paid', method = 'insurance' WHERE ID = ?");
        $stmt->bind_param("i", $paymentId);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}