<?php

require_once __DIR__ . "/../Model/config.php";
require_once __DIR__ . "/../Model/autoload.php";

include_once __DIR__ . "/../Model/entities/Address.php";
include_once __DIR__ . "/../Model/entities/User.php";
include_once __DIR__ . "/../Model/entities/Doctor.php";

use Model\entities\User;
use Model\entities\Address;

class AuthController extends Controller
{
    private $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User($this->db);
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $errors = [];

            // Validate input
            if (empty($username)) {
                $errors['username'] = 'Username is required';
            }
            if (empty($password)) {
                $errors['password'] = 'Password is required';
            }

            if (empty($errors)) {
                $user = $this->userModel->getByUsername($username);

                if ($user && password_verify($password, $user['password'])) {
                    // Set session variables
                    $_SESSION['user_id'] = $user['userID'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['user_type'] = $user['roleID'];

                    // Redirect based on role
                    if ($user['roleID'] === 1) {
                        $this->redirect('/clinicus/admin/dashboard');
                    } else if ($user['roleID'] === 2) {
                        $this->redirect('/clinicus/doctor/dashboard');
                    } else {
                        $this->redirect('/clinicus/patient/dashboard');
                    }
                } else {
                    $errors['login'] = 'Invalid username or password';
                }
            }

            // If we get here, there were errors
            $this->render('auth/login', ['errors' => $errors, 'username' => $username]);
        } else {
            // Show login form
            $this->render('auth/login');
        }
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];

            // Get form data
            $first_name = trim($_POST['first_name'] ?? '');
            $last_name = trim($_POST['last_name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $address = trim($_POST['address'] ?? '');
            $dob = trim($_POST['dob'] ?? '');
            $role = trim($_POST['role'] ?? '');
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            // Get doctor-specific fields if role is doctor
            $doctorType = trim($_POST['doctorType'] ?? '');
            $yearsOfExperience = trim($_POST['yearsOfExperience'] ?? '');
            $consultation_fee = trim($_POST['consultation_fee'] ?? '');

            // Validate first name
            if (empty($first_name)) {
                $errors['first_name'] = 'First name is required';
            }

            // Validate last name
            if (empty($last_name)) {
                $errors['last_name'] = 'Last name is required';
            }

            // Validate email
            if (empty($email)) {
                $errors['email'] = 'Email is required';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Invalid email format';
            }

            // Validate phone
            if (empty($phone)) {
                $errors['phone'] = 'Phone number is required';
            } elseif (!preg_match('/^[0-9]{10,15}$/', $phone)) {
                $errors['phone'] = 'Invalid phone number format';
            }

            // Validate address
            if (empty($address)) {
                $errors['address'] = 'Address is required';
            }

            // Validate date of birth
            if (empty($dob)) {
                $errors['dob'] = 'Date of birth is required';
            } else {
                $dob_date = new DateTime($dob);
                $today = new DateTime();
                $age = $today->diff($dob_date)->y;
                if ($age < 18) {
                    $errors['dob'] = 'You must be at least 18 years old';
                }
            }

            // Validate role
            if (empty($role)) {
                $errors['role'] = 'Role is required';
            } elseif (!in_array($role, ['1', '2', '3'])) {
                $errors['role'] = 'Invalid role selected';
            }

            // Validate doctor-specific fields if role is doctor
            if ($role === '2') {
                if (empty($doctorType)) {
                    $errors['doctorType'] = 'Specialization is required';
                }
                if (empty($yearsOfExperience)) {
                    $errors['yearsOfExperience'] = 'Years of experience is required';
                } elseif (!is_numeric($yearsOfExperience) || $yearsOfExperience < 0 || $yearsOfExperience > 50) {
                    $errors['yearsOfExperience'] = 'Years of experience must be between 0 and 50';
                }
                if (empty($consultation_fee)) {
                    $errors['consultation_fee'] = 'Consultation fee is required';
                } elseif (!is_numeric($consultation_fee) || $consultation_fee < 0) {
                    $errors['consultation_fee'] = 'Consultation fee must be a positive number';
                }
            }

            // Validate username
            if (empty($username)) {
                $errors['username'] = 'Username is required';
            } elseif (strlen($username) < 3) {
                $errors['username'] = 'Username must be at least 3 characters';
            }

            // Validate password
            if (empty($password)) {
                $errors['password'] = 'Password is required';
            } elseif (strlen($password) < 8) {
                $errors['password'] = 'Password must be at least 8 characters';
            }

            // Validate confirm password
            if (empty($confirm_password)) {
                $errors['confirm_password'] = 'Please confirm your password';
            } elseif ($password !== $confirm_password) {
                $errors['confirm_password'] = 'Passwords do not match';
            }

            if (empty($errors)) {
                try {
                    // First check if address exists
                    $addressModel = new Address($this->db);
                    $existingAddress = $addressModel->getByAddress($address);

                    if ($existingAddress) {
                        $addressId = $existingAddress['ID'];
                    } else {
                        // Create new address if it doesn't exist
                        $addressId = $addressModel->create([
                            'name' => $address,
                        ]);
                    }

                    if (!$addressId) {
                        throw new \Exception('Failed to create address');
                    }

                    // Then create the user with the address ID
                    $user = new User($this->db);
                    $userId = $user->create([
                        'FirstName' => $first_name,
                        'LastName' => $last_name,
                        'email' => $email,
                        'phone' => $phone,
                        'addressID' => $addressId,
                        'dob' => $dob,
                        'roleID' => $role,
                        'username' => $username,
                        'password' => password_hash($password, PASSWORD_DEFAULT)
                    ]);

                    if (!$userId) {
                        throw new \Exception('Failed to create user');
                    }

                    // If role is doctor, create doctor record
                    if ($role === '2') {
                        $doctorModel = new \Model\entities\Doctor($this->db);
                        $doctorCreated = $doctorModel->create([
                            'userID' => $userId,
                            'doctorType' => $doctorType,
                            'yearsOfExperince' => $yearsOfExperience,
                            'rating' => 0,
                            'consultation_fee' => $consultation_fee
                        ]);

                        if (!$doctorCreated) {
                            throw new \Exception('Failed to create doctor record');
                        }
                    }

                    $_SESSION['success'] = 'Registration successful! Please login.';
                    header('Location: /clinicus/auth/login');
                    exit;
                } catch (\Exception $e) {
                    $errors['register'] = 'An error occurred during registration. ' . $e->getMessage();
                }
            }

            // If there are errors, return to the registration form with the errors
            $this->render('auth/register', [
                'errors' => $errors,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'phone' => $phone,
                'address' => $address,
                'dob' => $dob,
                'role' => $role,
                'username' => $username,
                'doctorType' => $doctorType,
                'yearsOfExperience' => $yearsOfExperience,
                'consultation_fee' => $consultation_fee,
                'specializations' => (new \Model\entities\Doctor($this->db))->getAllSpecializations()
            ]);
        } else {
            // Show registration form with specializations
            $this->render('auth/register', [
                'specializations' => (new \Model\entities\Doctor($this->db))->getAllSpecializations()
            ]);
        }
    }

    public function logout()
    {
        // Destroy session
        session_destroy();

        // Redirect to login
        $this->redirect('/clinicus/auth/login');
    }

    public function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    public function requireLogin()
    {
        if (!$this->isLoggedIn()) {
            header('Location: /clinicus/auth/login');
            exit;
        }
    }

    public function requireRole($role)
    {
        $this->requireLogin();
        if ($_SESSION['user_type'] !== $role) {
            header('Location: /clinicus/auth');
            exit;
        }
    }
}