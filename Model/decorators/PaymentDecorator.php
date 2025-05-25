<?php
// Model/decorators/PaymentDecorator.php

require_once __DIR__ . '/../interfaces/IPayment.php';

abstract class PaymentDecorator implements IPayment
{
    protected $payment;

    public function __construct(IPayment $payment)
    {
        $this->payment = $payment;
    }

    public function processPayment($data)
    {
        return $this->payment->processPayment($data);
    }

    public function validatePayment($data)
    {
        return $this->payment->validatePayment($data);
    }

    public function getPaymentDetails($id)
    {
        return $this->payment->getPaymentDetails($id);
    }
}