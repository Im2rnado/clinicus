<?php
require_once "Controller.php";
require_once "Model/entities/Doctor.php";
require_once "Model/entities/Appointment.php";
require_once "Model/entities/MedicalHistory.php";
require_once "Model/entities/User.php";

class DoctorController extends Controller
{
    private $doctorModel;
    private $appointmentModel;
    private $medicalHistoryModel;
    private $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->doctorModel = new \Model\entities\Doctor($this->db);
        $this->appointmentModel = new \Model\entities\Appointment($this->db);
        $this->medicalHistoryModel = new \Model\entities\MedicalHistory($this->db);
        $this->userModel = new \Model\entities\User($this->db);
    }

    public function index()
    {
        $this->dashboard();
    }

    public function dashboard()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 2) {
            header('Location: /clinicus/auth/login');
            exit;
        }

        // Get doctor's information
        $doctorId = $_SESSION['user_id'];
        $doctor = $this->doctorModel->read($doctorId);

        if (!$doctor) {
            $_SESSION['error'] = "Doctor information not found.";
            header('Location: /clinicus/auth/login');
            exit;
        }

        // Get today's appointments
        $todayAppointments = $this->appointmentModel->getAppointmentsByDoctorAndDate($doctor['ID'], date('Y-m-d'));

        // Get upcoming appointments
        $upcomingAppointments = $this->appointmentModel->getUpcomingAppointmentsByDoctor($doctor['ID']);

        // Get total patients
        $totalPatients = $this->doctorModel->getTotalPatients($doctor['ID']);

        // Get total appointments
        $totalAppointments = $this->appointmentModel->getTotalAppointmentsByDoctor($doctor['ID']);

        $this->render('doctors/dashboard', [
            'doctor' => $doctor,
            'todayAppointments' => $todayAppointments ?? [],
            'upcomingAppointments' => $upcomingAppointments ?? [],
            'totalPatients' => $totalPatients ?? 0,
            'totalAppointments' => $totalAppointments ?? 0
        ]);
    }

    public function appointments()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 2) {
            header('Location: /clinicus/auth/login');
            exit;
        }

        $doctorId = $_SESSION['user_id'];
        $doctor = $this->doctorModel->read($doctorId);

        if (!$doctor) {
            $_SESSION['error'] = "Doctor information not found.";
            header('Location: /clinicus/auth/login');
            exit;
        }

        $appointments = $this->appointmentModel->getAllAppointmentsByDoctor($doctor['ID']);

        $this->render('doctors/appointments', [
            'appointments' => $appointments ?? [],
            'doctor' => $doctor
        ]);
    }

    public function viewAppointment($id)
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 2) {
            header('Location: /clinicus/auth/login');
            exit;
        }

        $appointment = $this->appointmentModel->read($id);

        if (!$appointment) {
            $_SESSION['error'] = "Appointment not found.";
            header('Location: /clinicus/doctor/appointments');
            exit;
        }

        $patient = $this->userModel->read($appointment['userID']);
        $medicalHistory = $this->medicalHistoryModel->getMedicalHistoryByPatient($appointment['userID']);

        $this->render('doctors/view_appointment', [
            'appointment' => $appointment,
            'patient' => $patient,
            'medicalHistory' => $medicalHistory ?? []
        ]);
    }

    public function updateAppointmentStatus()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 2) {
            header('Location: /clinicus/auth/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $appointmentId = $_POST['appointment_id'] ?? null;
            $status = $_POST['status'] ?? null;

            if (!$appointmentId || !$status) {
                $_SESSION['error'] = "Invalid appointment data.";
                header('Location: /clinicus/doctor/appointments');
                exit;
            }

            $result = $this->appointmentModel->updateStatus($appointmentId, $status);

            if ($result) {
                $_SESSION['success'] = "Appointment status updated successfully.";
            } else {
                $_SESSION['error'] = "Failed to update appointment status.";
            }

            header('Location: /clinicus/doctor/appointments');
            exit;
        }
    }

    public function addMedicalHistory()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 2) {
            header('Location: /clinicus/auth/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'patientID' => $_POST['patient_id'] ?? null,
                'serviceID' => $_POST['service_id'] ?? null,
                'appointmentID' => $_POST['appointment_id'] ?? null,
                'description' => $_POST['description'] ?? '',
                'createdBy' => $_SESSION['user_id']
            ];

            if (!$data['patientID'] || !$data['appointmentID']) {
                $_SESSION['error'] = "Invalid medical history data.";
                header('Location: /clinicus/doctor/appointments');
                exit;
            }

            $result = $this->medicalHistoryModel->create($data);

            if ($result) {
                $_SESSION['success'] = "Medical history added successfully.";
            } else {
                $_SESSION['error'] = "Failed to add medical history.";
            }

            header('Location: /clinicus/doctor/viewAppointment/' . $data['appointmentID']);
            exit;
        }
    }

    public function profile()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 2) {
            header('Location: /clinicus/auth/login');
            exit;
        }

        $doctorId = $_SESSION['user_id'];
        $doctor = $this->userModel->read($doctorId);
        $doctorDetails = $this->doctorModel->read($doctorId);

        if (!$doctor || !$doctorDetails) {
            $_SESSION['error'] = "Doctor information not found.";
            header('Location: /clinicus/auth/login');
            exit;
        }

        // Get doctor statistics
        $totalPatients = $this->doctorModel->getTotalPatients($doctorId);
        $totalAppointments = $this->appointmentModel->getTotalAppointmentsByDoctor($doctorId);
        $rating = $doctorDetails['rating'] ?? 0;

        // Merge all data
        $doctorData = array_merge($doctor, $doctorDetails, [
            'totalPatients' => $totalPatients ?? 0,
            'totalAppointments' => $totalAppointments ?? 0,
            'rating' => $rating
        ]);

        $this->render('doctors/profile', [
            'doctor' => $doctorData
        ]);
    }

    public function updateProfile()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 2) {
            header('Location: /clinicus/auth/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $doctorId = $_SESSION['user_id'];
            $data = [
                'FirstName' => $_POST['first_name'] ?? '',
                'LastName' => $_POST['last_name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'consultation_fee' => $_POST['consultation_fee'] ?? 0
            ];

            // Validate required fields
            if (empty($data['FirstName']) || empty($data['LastName']) || empty($data['email'])) {
                $_SESSION['error'] = "Please fill in all required fields.";
                header('Location: /clinicus/doctor/profile');
                exit;
            }

            if ($this->userModel->update($doctorId, $data)) {
                $_SESSION['success'] = "Profile updated successfully!";
            } else {
                $_SESSION['error'] = "Failed to update profile.";
            }

            header('Location: /clinicus/doctor/profile');
            exit;
        }
    }

    public function patientHistory($patientId)
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 2) {
            header('Location: /clinicus/auth/login');
            exit;
        }

        $patient = $this->userModel->read($patientId);

        if (!$patient) {
            $_SESSION['error'] = "Patient not found.";
            header('Location: /clinicus/doctor/appointments');
            exit;
        }

        $medicalHistory = $this->medicalHistoryModel->getMedicalHistoryByPatient($patientId);

        $this->render('doctors/patient_history', [
            'medicalHistory' => $medicalHistory ?? [],
            'patient' => $patient
        ]);
    }
}