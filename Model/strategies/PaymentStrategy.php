<?php
// Model/strategies/PaymentStrategy.php

interface PaymentStrategy
{
    public function calculatePayment($data);
}