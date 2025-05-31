<?php

require_once __DIR__ . "/../Model/config.php";
require_once __DIR__ . "/../Model/autoload.php";
require_once __DIR__ . "/../Model/entities/ModelFactory.php";

use Model\entities\ModelFactory;

class AdminController
{
    private $db;
    private $userModel;
    private $doctorModel;
    private $patientModel;
    private $appointmentModel;
    private $staffModel;
    private $medicalHistoryModel;

    public function __construct()
    {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is logged in
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type'])) {
            header('Location: /clinicus/auth/login');
            exit;
        }

        // Check if user is an admin
        if ($_SESSION['user_type'] !== 1) {
            header('Location: /clinicus/auth/login');
            exit;
        }

        // Initialize database connection
        $this->db = (new DatabaseConnection())->connectToDB();

        // Initialize models
        $this->userModel = ModelFactory::getModelInstance('users', $this->db);
        $this->doctorModel = ModelFactory::getModelInstance('doctors', $this->db);
        $this->patientModel = ModelFactory::getModelInstance('patients', $this->db);
        $this->appointmentModel = ModelFactory::getModelInstance('appointments', $this->db);
        $this->staffModel = ModelFactory::getModelInstance('staff', $this->db);
        $this->medicalHistoryModel = ModelFactory::getModelInstance('medical_history', $this->db);
    }

    private function render($view, $data = [])
    {
        // Extract data to make variables available in view
        extract($data);

        // Start output buffering
        ob_start();

        // Include the view file
        $viewFile = __DIR__ . "/../views/admin/{$view}.php";
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            throw new Exception("View file not found: {$viewFile}");
        }

        // Get the contents of the buffer
        $content = ob_get_clean();

        // Include the layout
        require __DIR__ . "/../views/admin/layout.php";
    }

    public function index()
    {
        $this->dashboard();
    }

    public function dashboard()
    {
        try {
            // Get counts using mysqli style
            $stats = [
                'totalUsers' => $this->userModel->getCount(),
                'totalDoctors' => $this->doctorModel->getCount(),
                'totalPatients' => $this->patientModel->getCount(),
                'totalAppointments' => $this->appointmentModel->getCount(),
                'totalStaff' => $this->staffModel->getCount(),
                'totalMedicalHistory' => $this->medicalHistoryModel->getCount(),
            ];

            $recentAppointments = $this->getRecentAppointments();

            $this->render('dashboard', [
                'stats' => $stats,
                'recentAppointments' => $recentAppointments
            ]);
        } catch (Exception $e) {
            error_log('Dashboard Error: ' . $e->getMessage());
            $this->render('dashboard', [
                'stats' => [
                    'totalUsers' => '0',
                    'totalDoctors' => '0',
                    'totalPatients' => '0',
                    'totalAppointments' => '0',
                    'totalStaff' => '0',
                    'totalMedicalHistory' => '0',
                ],
                'recentAppointments' => []
            ]);
        }
    }

    public function users()
    {
        try {
            // Get all users
            $users = $this->userModel->readAll();

            $this->render('users', [
                'users' => $users
            ]);
        } catch (Exception $e) {
            error_log('Users Error: ' . $e->getMessage());
            $_SESSION['error'] = 'Failed to load users. Please try again.';
            $this->render('users', [
                'users' => []
            ]);
        }
    }

    public function doctors()
    {
        try {
            // Get all doctors with their details
            $doctors = $this->doctorModel->readAll();

            $this->render('doctors', [
                'doctors' => $doctors
            ]);
        } catch (Exception $e) {
            error_log('Doctors Error: ' . $e->getMessage());
            $_SESSION['error'] = 'Failed to load doctors. ' . $e->getMessage();
            $this->render('doctors', [
                'doctors' => []
            ]);
        }
    }

    public function appointments()
    {
        try {
            // Get all appointments with related data
            $appointments = $this->appointmentModel->readAll();

            $this->render('appointments', [
                'appointments' => $appointments
            ]);
        } catch (Exception $e) {
            error_log('Appointments Error: ' . $e->getMessage());
            $_SESSION['error'] = 'Failed to load appointments. ' . $e->getMessage();
            $this->render('appointments', [
                'appointments' => []
            ]);
        }
    }

    public function manageTable($tableName)
    {
        try {
            $model = ModelFactory::getModelInstance($tableName, $this->db);
            $records = $model->readAll();

            // Get column names (from first record)
            $columns = [];
            if (!empty($records)) {
                $columns = array_keys($records[0]);
            }

            $this->render('table', [
                'title' => 'Edit ' . $tableName,
                'tableName' => $tableName,
                'records' => $records,
                'columns' => $columns
            ]);

        } catch (Exception $e) {
            echo 'Error has occurred: ' . $e->getMessage();
        }
    }

    private function getTotalPatients()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM Patients");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    private function getTotalDoctors()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM Doctors");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    private function getTodayAppointments()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM Appointment WHERE DATE(appointmentDate) = CURDATE()");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    private function getTotalRevenue()
    {
        $stmt = $this->db->prepare("SELECT SUM(totalPrice) as total FROM Appointment_Details");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }

    private function getRecentAppointments()
    {
        $stmt = $this->db->prepare("
            SELECT 
                a.ID,
                CONCAT(u1.FirstName, ' ', u1.LastName) as patientName,
                CONCAT(u2.FirstName, ' ', u2.LastName) as doctorName,
                a.appointmentDate as date,
                CASE 
                    WHEN a.status = 0 THEN 'Pending'
                    WHEN a.status = 1 THEN 'Confirmed'
                    WHEN a.status = 2 THEN 'Cancelled'
                    ELSE 'Completed'
                END as status,
                CASE 
                    WHEN a.status = 0 THEN 'warning'
                    WHEN a.status = 1 THEN 'success'
                    WHEN a.status = 2 THEN 'danger'
                    ELSE 'info'
                END as statusColor
            FROM Appointment a
            JOIN Users u1 ON a.userID = u1.userID
            JOIN Doctors d ON a.DoctorID = d.ID
            JOIN Users u2 ON d.userID = u2.userID
            ORDER BY a.appointmentDate DESC
            LIMIT 5
        ");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Appointments methods
    public function deleteAppointment($id)
    {
        try {
            if ($this->appointmentModel->delete($id)) {
                $_SESSION['success'] = 'Appointment deleted successfully.';
            } else {
                $_SESSION['error'] = 'Failed to delete appointment.';
            }
        } catch (Exception $e) {
            error_log('Delete Appointment Error: ' . $e->getMessage());
            $_SESSION['error'] = 'Failed to delete appointment: ' . $e->getMessage();
        }
        header('Location: /clinicus/admin/appointments');
        exit;
    }

    public function editAppointment($id)
    {
        try {
            $appointment = $this->appointmentModel->read($id);
            if (!$appointment) {
                $_SESSION['error'] = 'Appointment not found.';
                header('Location: /clinicus/admin/appointments');
                exit;
            }
            $this->render('appointments/edit', ['appointment' => $appointment]);
        } catch (Exception $e) {
            error_log('Edit Appointment Error: ' . $e->getMessage());
            $_SESSION['error'] = 'Failed to load appointment: ' . $e->getMessage();
            header('Location: /clinicus/admin/appointments');
            exit;
        }
    }

    // Doctors methods
    public function deleteDoctor($id)
    {
        try {
            if ($this->doctorModel->delete($id)) {
                $_SESSION['success'] = 'Doctor deleted successfully.';
            } else {
                $_SESSION['error'] = 'Failed to delete doctor.';
            }
        } catch (Exception $e) {
            error_log('Delete Doctor Error: ' . $e->getMessage());
            $_SESSION['error'] = 'Failed to delete doctor: ' . $e->getMessage();
        }
        header('Location: /clinicus/admin/doctors');
        exit;
    }

    public function editDoctor($id)
    {
        try {
            $doctor = $this->doctorModel->read($id);
            if (!$doctor) {
                $_SESSION['error'] = 'Doctor not found.';
                header('Location: /clinicus/admin/doctors');
                exit;
            }
            $this->render('doctors/edit', ['doctor' => $doctor]);
        } catch (Exception $e) {
            error_log('Edit Doctor Error: ' . $e->getMessage());
            $_SESSION['error'] = 'Failed to load doctor: ' . $e->getMessage();
            header('Location: /clinicus/admin/doctors');
            exit;
        }
    }

    // Users methods
    public function deleteUser($id)
    {
        try {
            if ($this->userModel->delete($id)) {
                $_SESSION['success'] = 'User deleted successfully.';
            } else {
                $_SESSION['error'] = 'Failed to delete user.';
            }
        } catch (Exception $e) {
            error_log('Delete User Error: ' . $e->getMessage());
            $_SESSION['error'] = 'Failed to delete user: ' . $e->getMessage();
        }
        header('Location: /clinicus/admin/users');
        exit;
    }

    public function editUser($id)
    {
        try {
            $user = $this->userModel->read($id);
            if (!$user) {
                $_SESSION['error'] = 'User not found.';
                header('Location: /clinicus/admin/users');
                exit;
            }
            $this->render('users/edit', ['user' => $user]);
        } catch (Exception $e) {
            error_log('Edit User Error: ' . $e->getMessage());
            $_SESSION['error'] = 'Failed to load user: ' . $e->getMessage();
            header('Location: /clinicus/admin/users');
            exit;
        }
    }
}
