<?php

use Model\decorators\HealthDecorator;
// Model/decorators/DiagnosisHistory.php

class DiagnosisHistory extends HealthDecorator
{
    public function getRecord($id)
    {
        return parent::getRecord($id);
    }

    public function updateRecord($id, $data)
    {
        return parent::updateRecord($id, $data);
    }

    public function addRecord($data)
    {
        // Add diagnosis-specific logic before/after base addRecord
        // Example: $data['type'] = 'diagnosis';
        return parent::addRecord($data);
    }

    public function getDescription()
    {
        // Add/override description for diagnosis
        return 'Diagnosis: ' . parent::getDescription();
    }
}
