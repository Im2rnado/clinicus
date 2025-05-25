<?php
// Model/abstract/AbstractPaymentMethod.php

abstract class AbstractPaymentMethod
{
    public $ID;
    public $name;
    public $createdAt;
    public $updatedAt;
    protected $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Abstract payment methods
    abstract public function processPayment($data);
    abstract public function validatePayment($data);
    abstract public function getPaymentDetails($id);
}