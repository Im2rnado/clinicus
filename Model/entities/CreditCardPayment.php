<?php
namespace Model\entities;

use IPayment;

class CreditCardPayment implements IPayment
{
    private $conn;
    public function __construct($db)
    {
        $this->conn = $db;
    }
    public function executePayment($paymentId)
    {
        $stmt = $this->conn->prepare("UPDATE Payment SET status = 'paid', method = 'credit_card' WHERE ID = ?");
        $stmt->bind_param("i", $paymentId);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}