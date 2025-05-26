<?php
// Model/interfaces/iHealthRecord.php
namespace Model\interfaces;

interface iHealthRecord {
    /**
     * Get health record data
     * 
     * @return array Record data
     */
    public function getData();
    
    /**
     * Get health record description
     * 
     * @return string Record description
     */
    public function getDescription();
}