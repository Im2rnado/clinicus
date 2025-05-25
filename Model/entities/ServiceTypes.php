<?php
// Model/entities/ServiceTypes.php

class ServiceTypes
{
    public $ID;
    public $serviceName;
    public $description;
    public $createdAt;
    public $updatedAt;
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($data)
    {
        $stmt = $this->conn->prepare("INSERT INTO Service_Types (serviceName, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $data['serviceName'], $data['description']);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function read($id = null)
    {
        if ($id === null) {
            $result = $this->conn->query("SELECT * FROM Service_Types");
            $types = [];
            while ($row = $result->fetch_object()) {
                $types[] = $row;
            }
            return $types;
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM Service_Types WHERE ID = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            $type = $res->fetch_object();
            $stmt->close();
            return $type;
        }
    }

    public function update($id, $data)
    {
        $stmt = $this->conn->prepare("UPDATE Service_Types SET serviceName = ?, description = ? WHERE ID = ?");
        $stmt->bind_param("ssi", $data['serviceName'], $data['description'], $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM Service_Types WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}