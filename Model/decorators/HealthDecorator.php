<?php
// Model/decorators/HealthDecorator.php
namespace Model\decorators;

use Model\interfaces\IHealthRecord;

abstract class HealthDecorator implements IHealthRecord
{
    protected $healthRecord;

    public function __construct(IHealthRecord $healthRecord)
    {
        $this->healthRecord = $healthRecord;
    }

    public function getData()
    {
        return $this->healthRecord->getData();
    }

    public function getDescription()
    {
        return $this->healthRecord->getDescription();
    }
}