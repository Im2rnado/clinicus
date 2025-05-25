<?php
// Model/interfaces/IMessageSender.php

interface IMessageSender
{
    public function sendMessage($recipient, $message);
    public function getMessageStatus($messageId);
}