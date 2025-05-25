<?php
// Model/entities/RenderPaymentMethods.php
namespace Model\entities;

class RenderPaymentMethods
{
    public $ID;
    public $pmID;
    public $HTML;
    public $createdAt;
    public $updatedAt;
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($data)
    {
        $stmt = $this->conn->prepare("INSERT INTO Render_Payment_Methods (pmID, HTML) VALUES (?, ?)");
        $stmt->bind_param("is", $data['pmID'], $data['HTML']);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function read($id = null)
    {
        if ($id === null) {
            $result = $this->conn->query("SELECT * FROM Render_Payment_Methods");
            $renders = [];
            while ($row = $result->fetch_object()) {
                $renders[] = $row;
            }
            return $renders;
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM Render_Payment_Methods WHERE ID = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            $render = $res->fetch_object();
            $stmt->close();
            return $render;
        }
    }

    public function update($id, $data)
    {
        $stmt = $this->conn->prepare("UPDATE Render_Payment_Methods SET pmID = ?, HTML = ? WHERE ID = ?");
        $stmt->bind_param("isi", $data['pmID'], $data['HTML'], $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM Render_Payment_Methods WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}