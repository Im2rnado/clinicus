<?php
include_once __DIR__ . "/../Model/entities/Appointment.php";
include_once __DIR__ . "/../Model/entities/Doctor.php";
include_once __DIR__ . "/../Model/entities/Payment.php";
class AppointmentsController extends Controller
{
    private $appointmentModel;
    private $doctorModel;
    private $paymentModel;

    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->appointmentModel = new \Model\entities\Appointment(db: $this->db);
        $this->doctorModel = new \Model\entities\Doctor($this->db);
        $this->paymentModel = new \Model\entities\Payment($this->db);
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
            if (isset($_POST['specialization']) && !isset($_POST['doctor_id'])) {
                $specializationId = (int) $_POST['specialization'];
                $doctors = $this->doctorModel->getDoctorsBySpecialization($specializationId);

                $this->render('appointments/create', [
                    'step' => 2,
                    'specialization' => $specializationId,
                    'doctors' => $doctors
                ]);
                return;
            }

            // If doctor is selected, show payment form
            if (isset($_POST['doctor_id']) && !isset($_POST['payment_method'])) {
                $doctorId = $_POST['doctor_id'] ?? '';
                $appointmentDate = $_POST['appointment_date'] ?? '';
                $appointmentTime = $_POST['appointment_time'] ?? '';
                $reason = $_POST['reason'] ?? '';
                $specialization = $_POST['specialization'] ?? '';

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
                    // Get doctor details for payment
                    $doctor = $this->doctorModel->read($doctorId);
                    // Convert doctor object to array
                    $doctor = (array) $doctor;

                    // Get payment methods from database
                    $paymentMethods = $this->paymentModel->getPaymentMethods();
                    $cardOptions = $this->paymentModel->getPaymentOptions('Payment');
                    $coverageOptions = $this->paymentModel->getPaymentOptions('Coverage');

                    $this->render('appointments/create', [
                        'step' => 3,
                        'doctor' => $doctor,
                        'doctor_id' => $doctorId,
                        'appointment_date' => $appointmentDate,
                        'appointment_time' => $appointmentTime,
                        'reason' => $reason,
                        'specialization' => $specialization,
                        'paymentMethods' => $paymentMethods,
                        'cardOptions' => $cardOptions,
                        'coverageOptions' => $coverageOptions
                    ]);
                    return;
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

            // Handle final payment submission
            if (isset($_POST['payment_method'])) {
                try {
                    // Start transaction
                    $this->db->begin_transaction();

                    // Create appointment
                    $appointmentData = [
                        'DoctorID' => $_POST['doctor_id'],
                        'userID' => $_SESSION['user_id'],
                        'appointmentDate' => $_POST['appointment_date'] . ' ' . $_POST['appointment_time'],
                        'reason' => $_POST['reason'],
                        'status' => 0, // Pending
                        'createdAt' => date('Y-m-d H:i:s'),
                        'updatedAt' => date('Y-m-d H:i:s')
                    ];

                    $appointmentId = $this->appointmentModel->create($appointmentData);

                    if (!$appointmentId) {
                        throw new Exception('Failed to create appointment');
                    }

                    // Get doctor's consultation fee
                    $doctor = $this->doctorModel->read($_POST['doctor_id']);
                    $amount = $doctor['consultation_fee'] ?? 0;

                    // Create payment record
                    $paymentData = [
                        'appointmentID' => $appointmentId,
                        'userID' => $_SESSION['user_id'],
                        'amount' => $amount,
                        'paymentMethod' => $_POST['payment_method'],
                        'status' => 'pending',
                        'transactionID' => uniqid('TRANS_'),
                        'createdAt' => date('Y-m-d H:i:s'),
                        'updatedAt' => date('Y-m-d H:i:s')
                    ];

                    $paymentId = $this->paymentModel->create($paymentData);

                    if (!$paymentId) {
                        throw new Exception('Failed to create payment record');
                    }

                    // Commit transaction
                    $this->db->commit();

                    // Set success message
                    $_SESSION['success'] = 'Appointment booked successfully! Please complete the payment.';

                    // Redirect to payment confirmation page
                    header('Location: /clinicus/payments/' . $paymentId);
                    exit;

                } catch (Exception $e) {
                    // Rollback transaction on error
                    $this->db->rollback();
                    $_SESSION['error'] = 'Failed to book appointment: ' . $e->getMessage();
                    header('Location: /clinicus/appointments/create');
                    exit;
                }
            }
        }

        // Show initial form (Step 1)
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