<?php
// Model/entities/DosageForm.php

class DosageForm
{
    public $ID;
    public $Form;
    public $createdAt;
    public $updatedAt;
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($data)
    {
        $stmt = $this->conn->prepare("INSERT INTO Dosage_Form (Form) VALUES (?)");
        $stmt->bind_param("s", $data['Form']);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function read($id = null)
    {
        if ($id === null) {
            $result = $this->conn->query("SELECT * FROM Dosage_Form");
            $forms = [];
            while ($row = $result->fetch_object()) {
                $forms[] = $row;
            }
            return $forms;
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM Dosage_Form WHERE ID = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            $form = $res->fetch_object();
            $stmt->close();
            return $form;
        }
    }

    public function update($id, $data)
    {
        $stmt = $this->conn->prepare("UPDATE Dosage_Form SET Form = ? WHERE ID = ?");
        $stmt->bind_param("si", $data['Form'], $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM Dosage_Form WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}