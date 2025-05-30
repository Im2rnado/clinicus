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

            // If payment is submitted, process appointment and payment
            if (isset($_POST['payment_method'])) {
                $doctorId = $_POST['doctor_id'] ?? '';
                $appointmentDate = $_POST['appointment_date'] ?? '';
                $appointmentTime = $_POST['appointment_time'] ?? '';
                $reason = $_POST['reason'] ?? '';
                $paymentMethod = $_POST['payment_method'] ?? '';
                $cardNumber = $_POST['card_number'] ?? '';
                $expiryDate = $_POST['expiry_date'] ?? '';
                $cvv = $_POST['cvv'] ?? '';

                // Validate payment details
                if (empty($paymentMethod)) {
                    $errors['payment_method'] = 'Please select a payment method';
                }
                if (empty($cardNumber) || strlen($cardNumber) !== 16) {
                    $errors['card_number'] = 'Please enter a valid card number';
                }
                if (empty($expiryDate) || !preg_match('/^(0[1-9]|1[0-2])\/([0-9]{2})$/', $expiryDate)) {
                    $errors['expiry_date'] = 'Please enter a valid expiry date';
                }
                if (empty($cvv) || strlen($cvv) < 3 || strlen($cvv) > 4) {
                    $errors['cvv'] = 'Please enter a valid CVV';
                }

                if (empty($errors)) {
                    // Get doctor details for payment amount
                    $doctor = $this->doctorModel->read($doctorId);
                    // Convert doctor object to array
                    $doctor = (array) $doctor;
                    $amount = $doctor['consultation_fee'];

                    // Create payment record
                    $paymentModel = new \Model\entities\Payment($this->db);
                    $paymentData = [
                        'appointmentID' => null, // Will be updated after appointment creation
                        'userID' => $_SESSION['user_id'],
                        'amount' => $amount,
                        'paymentMethod' => $paymentMethod,
                        'status' => 'completed',
                        'transactionID' => uniqid('TRX')
                    ];

                    // Start transaction
                    $this->db->beginTransaction();

                    try {
                        // Create appointment
                        $appointmentDateTime = $appointmentDate . ' ' . $appointmentTime;
                        $appointmentData = [
                            'DoctorID' => $doctorId,
                            'userID' => $_SESSION['user_id'],
                            'appointmentDate' => $appointmentDateTime,
                            'reason' => $reason,
                            'status' => 1 // Set to confirmed since payment is completed
                        ];

                        if ($this->appointmentModel->create($appointmentData)) {
                            $appointmentId = $this->db->lastInsertId();

                            // Update payment with appointment ID
                            $paymentData['appointmentID'] = $appointmentId;

                            if ($paymentModel->create($paymentData)) {
                                $this->db->commit();
                                $_SESSION['success'] = 'Appointment booked and payment completed successfully!';
                                $this->redirect('/clinicus/appointments');
                            } else {
                                throw new \Exception('Failed to create payment record');
                            }
                        } else {
                            throw new \Exception('Failed to create appointment');
                        }
                    } catch (\Exception $e) {
                        $this->db->rollBack();
                        $errors['system'] = 'Failed to process payment: ' . $e->getMessage();
                    }
                }

                // If we get here, there were errors
                $doctor = $this->doctorModel->read($doctorId);
                $this->render('appointments/create', [
                    'step' => 3,
                    'errors' => $errors,
                    'doctor' => $doctor,
                    'doctor_id' => $doctorId,
                    'appointment_date' => $appointmentDate,
                    'appointment_time' => $appointmentTime,
                    'reason' => $reason,
                    'specialization' => $_POST['specialization']
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