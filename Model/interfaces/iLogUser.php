<?php
// Model/interfaces/iLogUser.php
namespace Model\interfaces;

interface iLogUser {
    /**
     * Log user activity
     * 
     * @param int $userId User ID
     * @param string $action Action performed
     * @param array $details Additional details
     * @return bool Success status
     */
    public function logUserActivity($userId, $action, $details = []);
    
    /**
     * Get user activity logs
     * 
     * @param int $userId User ID
     * @return array User activity logs
     */
    public function getUserLogs($userId);
}