<?php
// Model/decorators/MessageDecorator.php

require_once __DIR__ . '/../interfaces/IMessageSender.php';

abstract class MessageDecorator implements IMessageSender
{
    protected $sender;

    public function __construct(IMessageSender $sender)
    {
        $this->sender = $sender;
    }

    public function sendMessage($recipient, $message)
    {
        return $this->sender->sendMessage($recipient, $message);
    }

    public function getMessageStatus($messageId)
    {
        return $this->sender->getMessageStatus($messageId);
    }
}