<?php
// Model/abstract/AbstractUser.php
namespace Model\abstract;

use Model\interfaces\ILogUser;

abstract class AbstractUser implements ILogUser
{
    public $userID;
    public $FirstName;
    public $LastName;
    public $username;
    public $password;
    public $dob;
    public $addressID;
    public $roleID;
    public $createdAt;
    public $updatedAt;

    protected $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * Get user by ID
     * 
     * @param int $userId
     * @return array|bool User data or false if not found
     */
    abstract public function getUserById($userId);

    /**
     * Create a new user
     * 
     * @param array $userData
     * @return int|bool New user ID or false on failure
     */
    abstract public function createUser($userData);

    /**
     * Update user
     * 
     * @param int $userId
     * @param array $userData
     * @return bool Success status
     */
    abstract public function updateUser($userId, $userData);

    /**
     * Delete user
     * 
     * @param int $userId
     * @return bool Success status
     */
    abstract public function deleteUser($userId);

    /**
     * Log user activity
     * 
     * @param int $userId User ID
     * @param string $action Action performed
     * @param array $details Additional details
     * @return bool Success status
     */
    public function logUserActivity($userId, $action, $details = [])
    {
        $sql = "INSERT INTO audit_logs (UserId, Action, Details, Timestamp) 
                VALUES (?, ?, ?, NOW())";

        $stmt = $this->conn->prepare($sql);
        $detailsJson = json_encode($details);

        return $stmt->execute([$userId, $action, $detailsJson]);
    }

    /**
     * Get user activity logs
     * 
     * @param int $userId User ID
     * @return array User activity logs
     */
    public function getUserLogs($userId)
    {
        $sql = "SELECT * FROM audit_logs WHERE UserId = ? ORDER BY Timestamp DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Abstract CRUD methods
    abstract public function create($data);
    abstract public function read($id);
    abstract public function update($id, $data);
    abstract public function delete($id);
}