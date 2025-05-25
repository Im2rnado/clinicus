<?php
// Model/entities/PaymentValue.php

class PaymentValue
{
    public $ID;
    public $PMOpID;
    public $value;
    public $appointementID;
    public $createdAt;
    public $updatedAt;
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($data)
    {
        $stmt = $this->conn->prepare("INSERT INTO Payment_Method_Values (PMOpID, value, appointementID) VALUES (?, ?, ?)");
        $stmt->bind_param("idi", $data['PMOpID'], $data['value'], $data['appointementID']);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function read($id = null)
    {
        if ($id === null) {
            $result = $this->conn->query("SELECT * FROM Payment_Method_Values");
            $values = [];
            while ($row = $result->fetch_object()) {
                $values[] = $row;
            }
            return $values;
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM Payment_Method_Values WHERE ID = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            $value = $res->fetch_object();
            $stmt->close();
            return $value;
        }
    }

    public function update($id, $data)
    {
        $stmt = $this->conn->prepare("UPDATE Payment_Method_Values SET PMOpID = ?, value = ?, appointementID = ? WHERE ID = ?");
        $stmt->bind_param("idii", $data['PMOpID'], $data['value'], $data['appointementID'], $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM Payment_Method_Values WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}