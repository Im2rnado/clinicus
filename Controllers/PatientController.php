<?php

require_once __DIR__ . "/../Model/config.php";
require_once __DIR__ . "/../Model/autoload.php";

include_once __DIR__ . "/../Model/entities/Appointment.php";
include_once __DIR__ . "/../Model/entities/MedicalHistory.php";

use Model\entities\Appointment;
use Model\entities\MedicalHistory;

class PatientController extends Controller
{
    private $appointmentModel;
    private $medicalHistoryModel;

    public function __construct()
    {
        parent::__construct();
        $this->requireRole(3); // Require Patient role
        $this->appointmentModel = new Appointment($this->db);
        $this->medicalHistoryModel = new MedicalHistory($this->db);
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

        $this->render('patient/medical-history', [
            'title' => 'Medical History',
            'history' => $history
        ]);
    }

    public function prescriptions()
    {
        $patientId = $_SESSION['user_id'];
        $prescriptions = $this->medicalHistoryModel->getPrescriptions($patientId);

        $this->render('patient/prescriptions', [
            'title' => 'My Prescriptions',
            'prescriptions' => $prescriptions
        ]);
    }
}