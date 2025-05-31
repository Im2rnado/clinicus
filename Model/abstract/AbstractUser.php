<?php
// Model/abstract/AbstractUser.php
namespace Model\abstract;
abstract class AbstractUser
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

    // Abstract CRUD methods
    abstract public function create($data);
    abstract public function read($id);
    abstract public function update($id, $data);
    abstract public function delete($id);
}