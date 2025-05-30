<?php

require_once __DIR__ . "/../Model/config.php";
require_once __DIR__ . "/../Model/autoload.php";

include_once __DIR__ . "/../Model/entities/Appointment.php";
include_once __DIR__ . "/../Model/entities/MedicalHistory.php";
include_once __DIR__ . "/../Model/entities/User.php";
include_once __DIR__ . "/../Model/entities/Address.php";
include_once __DIR__ . "/../Model/entities/Messages.php";

use Model\entities\Appointment;
use Model\entities\MedicalHistory;
use Model\entities\User;
use Model\entities\Address;
use Model\entities\Messages;

class PatientController extends Controller
{
    private $appointmentModel;
    private $medicalHistoryModel;
    private $userModel;
    private $addressModel;
    private $messageModel;

    public function __construct()
    {
        parent::__construct();
        $this->requireRole(3); // Require Patient role
        $this->appointmentModel = new Appointment($this->db);
        $this->medicalHistoryModel = new MedicalHistory($this->db);
        $this->userModel = new User($this->db);
        $this->addressModel = new Address($this->db);
        $this->messageModel = new Messages($this->db);
    }

    public function index()
    {
        $this->dashboard();
    }

    public function dashboard()
    {
        $patientId = $_SESSION['user_id'];

        // Get upcoming appointments
        $upcomingAppointments = $this->appointmentModel->getUpcomingAppointments($patientId);

        // Get recent medical history
        $recentHistory = $this->medicalHistoryModel->getRecentHistory($patientId);

        $this->render('patient/dashboard', [
            'title' => 'Patient Dashboard',
            'upcomingAppointments' => $upcomingAppointments,
            'recentHistory' => $recentHistory
        ]);
    }

    public function appointments()
    {
        $patientId = $_SESSION['user_id'];
        $appointments = $this->appointmentModel->getAllAppointments($patientId);

        $this->render('patient/appointments', [
            'title' => 'My Appointments',
            'appointments' => $appointments
        ]);
    }

    public function medicalHistory()
    {
        $patientId = $_SESSION['user_id'];
        $history = $this->medicalHistoryModel->getAllHistory($patientId);

        $this->render('patient/medicalHistory', [
            'title' => 'Medical History',
            'history' => $history
        ]);
    }

    public function profile()
    {
        $userId = $_SESSION['user_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];

            // Get form data
            $first_name = trim($_POST['first_name'] ?? '');
            $last_name = trim($_POST['last_name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $address = trim($_POST['address'] ?? '');
            $dob = trim($_POST['dob'] ?? '');

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

            if (empty($errors)) {
                try {
                    // Check if address exists
                    $existingAddress = $this->addressModel->getByAddress($address);

                    if ($existingAddress) {
                        $addressId = $existingAddress['ID'];
                    } else {
                        // Create new address
                        $addressId = $this->addressModel->create([
                            'name' => $address
                        ]);
                    }

                    if (!$addressId) {
                        throw new Exception('Failed to update address');
                    }

                    // Update user profile
                    $result = $this->userModel->update($userId, [
                        'FirstName' => $first_name,
                        'LastName' => $last_name,
                        'email' => $email,
                        'phone' => $phone,
                        'addressID' => $addressId,
                        'dob' => $dob
                    ]);

                    if ($result) {
                        $_SESSION['success'] = 'Profile updated successfully';
                        $this->redirect('/clinicus/patient/profile');
                    } else {
                        $errors['update'] = 'Failed to update profile';
                    }
                } catch (Exception $e) {
                    $errors['update'] = 'An error occurred: ' . $e->getMessage();
                }
            }

            // If there are errors, return to the profile form with the errors
            $this->render('patient/profile', [
                'title' => 'My Profile',
                'errors' => $errors,
                'user' => [
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'email' => $email,
                    'phone' => $phone,
                    'address' => $address,
                    'dob' => $dob
                ]
            ]);
        } else {
            // Get current user data
            $user = $this->userModel->read(id: $userId);
            $address = $this->addressModel->read($user->addressID);

            $this->render('patient/profile', [
                'title' => 'My Profile',
                'user' => [
                    'first_name' => $user->FirstName,
                    'last_name' => $user->LastName,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'address' => $address->name,
                    'dob' => $user->dob
                ]
            ]);
        }
    }

    public function messages()
    {
        $userId = $_SESSION['user_id'];

        // Get all messages for the patient
        $messages = $this->messageModel->getMessagesByUserId($userId);

        // Mark messages as read
        if (!empty($messages)) {
            $unreadIds = array_map(function ($message) {
                return $message['ID'];
            }, array_filter($messages, function ($message) {
                return !$message['is_read'];
            }));

            if (!empty($unreadIds)) {
                $this->messageModel->markAsRead($unreadIds);
            }
        }

        $this->render('patient/messages', [
            'title' => 'My Messages',
            'messages' => $messages
        ]);
    }
}