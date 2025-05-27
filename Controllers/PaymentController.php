<?php

class PaymentController extends Controller
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

    public function view($id)
    {
        $payment = $this->paymentModel->read($id);

        if ($payment && $payment['userID'] === $_SESSION['user_id']) {
            $this->render('payments/view', [
                'payment' => $payment
            ]);
        } else {
            $this->redirect('/clinicus/payments');
        }
    }
}