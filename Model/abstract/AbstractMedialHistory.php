<?php
// Model/abstract/AbstractMedicalHistory.php
namespace Model\abstract;

abstract class AbstractMedicalHistory {
    protected $db;
    protected $patientId;
    
    public function __construct($db, $patientId) {
        $this->db = $db;
        $this->patientId = $patientId;
    }
    
    /**
     * Get medical history
     * 
     * @return array Medical history records
     */
    abstract public function getHistory();
    
    /**
     * Add history record
     * 
     * @param array $data History data
     * @return int|bool New record ID or false on failure
     */
    abstract public function addRecord($data);
    
    /**
     * Update history record
     * 
     * @param int $recordId
     * @param array $data Updated history data
     * @return bool Success status
     */
    abstract public function updateRecord($recordId, $data);
    
    /**
     * Delete history record
     * 
     * @param int $recordId
     * @return bool Success status
     */
    abstract public function deleteRecord($recordId);
}