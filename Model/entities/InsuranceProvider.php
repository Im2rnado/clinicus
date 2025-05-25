<?php
// Model/entities/InsuranceProvider.php
namespace Model\entities;

class InsuranceProvider
{
    public $ID;
    public $name;
    public $discount;
    public $createdAt;
    public $updatedAt;
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($data)
    {
        $stmt = $this->conn->prepare("INSERT INTO Insurance_Provider (name, discount) VALUES (?, ?)");
        $stmt->bind_param("si", $data['name'], $data['discount']);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function read($id = null)
    {
        if ($id === null) {
            $result = $this->conn->query("SELECT * FROM Insurance_Provider");
            $providers = [];
            while ($row = $result->fetch_object()) {
                $providers[] = $row;
            }
            return $providers;
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM Insurance_Provider WHERE ID = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            $provider = $res->fetch_object();
            $stmt->close();
            return $provider;
        }
    }

    public function update($id, $data)
    {
        $stmt = $this->conn->prepare("UPDATE Insurance_Provider SET name = ?, discount = ? WHERE ID = ?");
        $stmt->bind_param("sii", $data['name'], $data['discount'], $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM Insurance_Provider WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}