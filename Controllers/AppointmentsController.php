<?php
include_once __DIR__ . "/../Model/entities/Appointment.php";
include_once __DIR__ . "/../Model/entities/Doctor.php";

class AppointmentsController extends Controller
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

            // If specialization is selected, show doctors
            if (isset($_POST['specialization'])) {
                $specializationId = (int) $_POST['specialization'];
                $doctors = $this->doctorModel->getDoctorsBySpecialization($specializationId);

                $this->render('appointments/create', [
                    'step' => 2,
                    'specialization' => $specializationId,
                    'doctors' => $doctors
                ]);
                return;
            }

            // If doctor is selected, process the appointment
            if (isset($_POST['doctor_id'])) {
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
                        $_SESSION['success'] = 'Appointment booked successfully!';
                        $this->redirect('/clinicus/appointments');
                    } else {
                        $errors['system'] = 'Failed to book appointment';
                    }
                }

                // If we get here, there were errors
                $this->render('appointments/create', [
                    'step' => 2,
                    'errors' => $errors,
                    'doctors' => $this->doctorModel->getDoctorsBySpecialization($_POST['specialization']),
                    'specialization' => $_POST['specialization'],
                    'doctor_id' => $doctorId,
                    'appointment_date' => $appointmentDate,
                    'appointment_time' => $appointmentTime,
                    'reason' => $reason
                ]);
                return;
            }
        }

        // Show specialization selection (step 1)
        $specializations = $this->doctorModel->getAllSpecializations();
        $this->render('appointments/create', [
            'step' => 1,
            'specializations' => $specializations
        ]);
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