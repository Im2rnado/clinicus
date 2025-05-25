<?php
// Model/entities/PaymentMethodOption.php

class PaymentMethodOption
{
    public $ID;
    public $paymentID;
    public $optionID;
    public $createdAt;
    public $updatedAt;
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($data)
    {
        $stmt = $this->conn->prepare("INSERT INTO Payment_Method_Options (paymentID, optionID) VALUES (?, ?)");
        $stmt->bind_param("ii", $data['paymentID'], $data['optionID']);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function read($id = null)
    {
        if ($id === null) {
            $result = $this->conn->query("SELECT * FROM Payment_Method_Options");
            $options = [];
            while ($row = $result->fetch_object()) {
                $options[] = $row;
            }
            return $options;
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM Payment_Method_Options WHERE ID = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            $option = $res->fetch_object();
            $stmt->close();
            return $option;
        }
    }

    public function update($id, $data)
    {
        $stmt = $this->conn->prepare("UPDATE Payment_Method_Options SET paymentID = ?, optionID = ? WHERE ID = ?");
        $stmt->bind_param("iii", $data['paymentID'], $data['optionID'], $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM Payment_Method_Options WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}