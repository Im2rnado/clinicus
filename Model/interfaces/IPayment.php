<?php
// Model/interfaces/IPayment.php

interface IPayment
{
    public function processPayment($data);
    public function validatePayment($data);
    public function getPaymentDetails($id);
}