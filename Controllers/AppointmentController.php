<?php

class AppointmentController extends Controller
{
    private $appointmentModel;
    private $doctorModel;

    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->appointmentModel = new \Model\entities\Appointment(db: $this->db);
        $this->doctorModel = new \Model\entities\Doctor($this->db);
    }

    public function index()
    {
        $userId = $_SESSION['user_id'];
        $appointments = $this->appointmentModel->getAppointmentsByUser($userId);

        $this->render('appointments/index', [
            'appointments' => $appointments
        ]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];

            // Validate input
            $doctorId = $_POST['doctor_id'] ?? '';
            $appointmentDate = $_POST['appointment_date'] ?? '';
            $appointmentTime = $_POST['appointment_time'] ?? '';
            $reason = $_POST['reason'] ?? '';

            if (empty($doctorId)) {
                $errors['doctor_id'] = 'Please select a doctor';
            }
            if (empty($appointmentDate)) {
                $errors['appointment_date'] = 'Please select a date';
            }
            if (empty($appointmentTime)) {
                $errors['appointment_time'] = 'Please select a time';
            }
            if (empty($reason)) {
                $errors['reason'] = 'Please provide a reason for your visit';
            }

            if (empty($errors)) {
                // Combine date and time
                $appointmentDateTime = $appointmentDate . ' ' . $appointmentTime;

                // Create appointment
                if (
                    $this->appointmentModel->create([
                        'doctorID' => $doctorId,
                        'userID' => $_SESSION['user_id'],
                        'appointmentDate' => $appointmentDateTime,
                        'reason' => $reason,
                        'status' => 'Pending'
                    ])
                ) {
                    $this->redirect('/clinicus/appointments');
                } else {
                    $errors['system'] = 'Failed to book appointment';
                }
            }

            // If we get here, there were errors
            $this->render('appointments/create', [
                'errors' => $errors,
                'doctors' => $this->doctorModel->getAll(),
                'doctor_id' => $doctorId,
                'appointment_date' => $appointmentDate,
                'appointment_time' => $appointmentTime,
                'reason' => $reason
            ]);
        } else {
            // Show appointment form
            $this->render('appointments/create', [
                'doctors' => $this->doctorModel->getAll()
            ]);
        }
    }

    public function cancel($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $appointment = $this->appointmentModel->read($id);

            if ($appointment && $appointment['userID'] === $_SESSION['user_id']) {
                $this->appointmentModel->update($id, ['status' => 'Cancelled']);
            }

            $this->redirect('/clinicus/appointments');
        }
    }
}