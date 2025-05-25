<?php
// Model/strategies/NotificationStrategy.php

interface NotificationStrategy
{
    public function sendNotification($recipient, $message);
}