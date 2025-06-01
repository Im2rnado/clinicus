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

    private function isTimeSlotAvailable($doctorId, $date, $time)
    {
        // Check if there's an active appointment (not cancelled) for this doctor at the given date and time
        $sql = "SELECT COUNT(*) as count FROM Appointment 
                WHERE DoctorID = ? 
                AND DATE(appointmentDate) = ? 
                AND TIME(appointmentDate) = ? 
                AND status != 2"; // 2 is cancelled status

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iss", $doctorId, $date, $time);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        return $row['count'] == 0;
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
                    // Check if the time slot is available
                    if (!$this->isTimeSlotAvailable($doctorId, $appointmentDate, $appointmentTime)) {
                        $errors['time_slot'] = 'This time slot is already booked. Please select a different time.';
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
                    // Check if the time slot is still available before proceeding
                    if (!$this->isTimeSlotAvailable($_POST['doctor_id'], $_POST['appointment_date'], $_POST['appointment_time'])) {
                        throw new Exception('This time slot is no longer available. Please select a different time.');
                    }

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
                    header('Location: /clinicus/payments/show/' . $paymentId);
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

    public function cancel($appointmentId)
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /clinicus/auth/login');
            exit;
        }

        // Verify the appointment belongs to the patient
        $appointment = $this->appointmentModel->read($appointmentId);
        if (!$appointment || $appointment['userID'] != $_SESSION['user_id']) {
            $_SESSION['error'] = "Invalid appointment or unauthorized access.";
            header('Location: /clinicus/appointments');
            exit;
        }

        // Check if appointment is in the future and not already cancelled
        $appointmentDate = strtotime($appointment['appointmentDate']);
        if ($appointmentDate < time()) {
            $_SESSION['error'] = "Cannot cancel past appointments.";
            header('Location: /clinicus/appointments');
            exit;
        }

        if ($appointment['status'] == 2) { // 2 = cancelled
            $_SESSION['error'] = "Appointment is already cancelled.";
            header('Location: /appointments');
            exit;
        }

        // Update appointment status to cancelled (2)
        $result = $this->appointmentModel->updateStatus($appointmentId, 2);

        if ($result) {
            $_SESSION['success'] = "Appointment cancelled successfully.";
        } else {
            $_SESSION['error'] = "Failed to cancel appointment.";
        }

        header('Location: /clinicus/appointments');
        exit;
    }
}