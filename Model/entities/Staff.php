<?php
// Model/entities/Staff.php

require_once 'User.php';

class Staff extends User
{
    public $ID;
    public $userID;
    public $hiredAt;
    public $departmentID;
    public $positionID;
    public $createdAt;
    public $updatedAt;

    protected $conn;

    public function __construct($db)
    {
        parent::__construct($db);
        $this->conn = $db;
    }

    public function create($data)
    {
        $stmt = $this->conn->prepare("INSERT INTO Staff (userID, hiredAt, departmentID, positionID) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isii", $data['userID'], $data['hiredAt'], $data['departmentID'], $data['positionID']);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function read($id = null)
    {
        if ($id === null) {
            $result = $this->conn->query("SELECT * FROM Staff");
            $staffs = [];
            while ($row = $result->fetch_object()) {
                $staffs[] = $row;
            }
            return $staffs;
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM Staff WHERE ID = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            $staff = $res->fetch_object();
            $stmt->close();
            return $staff;
        }
    }

    public function update($id, $data)
    {
        $stmt = $this->conn->prepare("UPDATE Staff SET hiredAt = ?, departmentID = ?, positionID = ? WHERE ID = ?");
        $stmt->bind_param("siii", $data['hiredAt'], $data['departmentID'], $data['positionID'], $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM Staff WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}