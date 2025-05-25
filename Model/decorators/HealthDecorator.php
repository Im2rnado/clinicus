<?php
// Model/decorators/HealthDecorator.php
namespace Model\decorators;

use Model\interfaces\iHealthRecord;

abstract class HealthDecorator implements iHealthRecord {
    protected $healthRecord;
    
    public function __construct(iHealthRecord $healthRecord) {
        $this->healthRecord = $healthRecord;
    }
    
    public function getData() {
        return $this->healthRecord->getData();
    }
    
    public function getDescription() {
        return $this->healthRecord->getDescription();
    }
}