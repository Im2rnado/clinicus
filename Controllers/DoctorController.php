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

        // Get doctor's information
        $doctorId = $_SESSION['user_id'];
        $doctor = $this->doctorModel->read($doctorId);

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
            'todayAppointments' => $todayAppointments,
            'upcomingAppointments' => $upcomingAppointments,
            'totalPatients' => $totalPatients,
            'totalAppointments' => $totalAppointments
        ]);
    }

    public function appointments()
    {
        $doctorId = $_SESSION['user_id'];
        $doctor = $this->doctorModel->read($doctorId);
        $appointments = $this->appointmentModel->getAllAppointmentsByDoctor($doctor['ID']);

        $this->render('doctors/appointments', [
            'appointments' => $appointments,
            'doctor' => $doctor
        ]);
    }

    public function viewAppointment($id)
    {
        $appointment = $this->appointmentModel->read($id);
        $patient = $this->userModel->read(id: $appointment['userID']);
        $medicalHistory = $this->medicalHistoryModel->getAllHistory($appointment['userID']);

        $this->render('doctors/view_appointment', [
            'appointment' => $appointment,
            'patient' => $patient,
            'medicalHistory' => $medicalHistory
        ]);
    }

    public function updateAppointmentStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $appointmentId = $_POST['appointment_id'];
            $status = $_POST['status'];

            $result = $this->appointmentModel->updateStatus($appointmentId, $status);

            if ($result) {
                $_SESSION['success'] = "Appointment status updated successfully";
            } else {
                $_SESSION['error'] = "Failed to update appointment status";
            }

            header('Location: /clinicus/doctor/appointments');
            exit;
        }
    }

    public function addMedicalHistory()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'patientID' => $_POST['patient_id'],
                'serviceID' => $_POST['service_id'],
                'appointmentID' => $_POST['appointment_id'],
                'description' => $_POST['description'],
                'createdBy' => $_SESSION['user_id']
            ];

            $result = $this->medicalHistoryModel->create($data);

            if ($result) {
                $_SESSION['success'] = "Medical history added successfully";
            } else {
                $_SESSION['error'] = "Failed to add medical history";
            }

            header('Location: /clinicus/doctor/viewAppointment/' . $_POST['appointment_id']);
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

        // Get doctor statistics
        $totalPatients = $this->doctorModel->getTotalPatients($doctorId);
        $totalAppointments = $this->appointmentModel->getTotalAppointmentsByDoctor($doctorId);
        $rating = $doctorDetails['rating'];

        // Merge all data
        $doctorData = array_merge($doctor, $doctorDetails, [
            'totalPatients' => $totalPatients,
            'totalAppointments' => $totalAppointments,
            'rating' => $rating ?? 0
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
                'FirstName' => $_POST['first_name'],
                'LastName' => $_POST['last_name'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'consultation_fee' => $_POST['consultation_fee']
            ];

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
        $medicalHistory = $this->medicalHistoryModel->getAllHistory($patientId);
        $patient = $this->userModel->read($patientId);

        $this->render('doctors/patient_history', [
            'medicalHistory' => $medicalHistory,
            'patient' => $patient
        ]);
    }
}