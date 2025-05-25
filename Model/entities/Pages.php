<?php
// Model/entities/Pages.php
namespace Model\entities;

class Pages
{
    public $ID;
    public $friendlyName;
    public $linkAddress;
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
        $stmt = $this->conn->prepare("INSERT INTO Pages (friendlyName, linkAddress, HTML) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $data['friendlyName'], $data['linkAddress'], $data['HTML']);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function read($id = null)
    {
        if ($id === null) {
            $result = $this->conn->query("SELECT * FROM Pages");
            $pages = [];
            while ($row = $result->fetch_object()) {
                $pages[] = $row;
            }
            return $pages;
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM Pages WHERE ID = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            $page = $res->fetch_object();
            $stmt->close();
            return $page;
        }
    }

    public function update($id, $data)
    {
        $stmt = $this->conn->prepare("UPDATE Pages SET friendlyName = ?, linkAddress = ?, HTML = ? WHERE ID = ?");
        $stmt->bind_param("sssi", $data['friendlyName'], $data['linkAddress'], $data['HTML'], $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM Pages WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}