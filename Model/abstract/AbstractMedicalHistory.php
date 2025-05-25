<?php
// Model/abstract/AbstractMedicalHistory.php

abstract class AbstractMedicalHistory
{
    public $ID;
    public $patientID;
    public $createdAt;
    public $updatedAt;
    protected $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Abstract health record methods
    abstract public function addRecord($data);
    abstract public function getRecord($id);
    abstract public function updateRecord($id, $data);
}