<?php

include_once 'Model/entities/Payment.php';
include_once 'Model/entities/Appointment.php';
include_once 'Model/entities/Doctor.php';

class PaymentsController extends Controller
{
    private $paymentModel;
    private $appointmentModel;
    private $doctorModel;

    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->paymentModel = new \Model\entities\Payment($this->db);
        $this->appointmentModel = new \Model\entities\Appointment($this->db);
        $this->doctorModel = new \Model\entities\Doctor($this->db);
    }

    public function index()
    {
        $userId = $_SESSION['user_id'];
        $payments = $this->paymentModel->getPaymentsByUser($userId);

        $this->render('payments/index', [
            'payments' => $payments
        ]);
    }

    public function show($id)
    {
        $payment = $this->paymentModel->read($id);

        if (!$payment) {
            $_SESSION['error'] = 'Payment not found';
            $this->redirect('/clinicus/payments');
            return;
        }

        // Get appointment details
        $appointment = $this->appointmentModel->read($payment['appointmentID']);

        // Get doctor details
        $doctor = $this->doctorModel->read($appointment['DoctorID']);

        // Get payment method details
        $paymentMethod = $this->paymentModel->getPaymentMethod($payment['paymentMethod']);

        $this->render('payments/view', [
            'payment' => $payment,
            'appointment' => $appointment,
            'doctor' => $doctor,
            'paymentMethod' => $paymentMethod
        ]);
    }

    public function create($appointmentId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];

            // Validate input
            $paymentMethod = $_POST['payment_method'] ?? '';
            $amount = $_POST['amount'] ?? '';

            if (empty($paymentMethod)) {
                $errors['payment_method'] = 'Please select a payment method';
            }
            if (empty($amount)) {
                $errors['amount'] = 'Amount is required';
            }

            if (empty($errors)) {
                // Create payment
                if (
                    $this->paymentModel->create([
                        'appointmentID' => $appointmentId,
                        'userID' => $_SESSION['user_id'],
                        'amount' => $amount,
                        'paymentMethod' => $paymentMethod,
                        'status' => 'completed',
                        'transactionID' => uniqid('TRX')
                    ])
                ) {
                    $this->redirect('/clinicus/payments');
                } else {
                    $errors['system'] = 'Failed to process payment';
                }
            }

            // If we get here, there were errors
            $this->render('payments/create', [
                'errors' => $errors,
                'appointment' => $this->appointmentModel->read($appointmentId),
                'payment_method' => $paymentMethod,
                'amount' => $amount
            ]);
        } else {
            // Show payment form
            $this->render('payments/create', [
                'appointment' => $this->appointmentModel->read($appointmentId),
                'paymentModel' => $this->paymentModel
            ]);
        }
    }
}