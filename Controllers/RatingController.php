<?php

class RatingController extends Controller
{
    private $ratingModel;
    private $appointmentModel;
    private $doctorModel;

    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->ratingModel = new \Model\entities\Rating($this->db);
        $this->appointmentModel = new \Model\entities\Appointment($this->db);
        $this->doctorModel = new \Model\entities\Doctor($this->db);
    }

    public function create($appointmentId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];

            // Validate input
            $rating = $_POST['rating'] ?? '';
            $comment = $_POST['comment'] ?? '';

            if (empty($rating)) {
                $errors['rating'] = 'Please select a rating';
            }

            if (empty($errors)) {
                // Get appointment details
                $appointment = $this->appointmentModel->read($appointmentId);

                if ($appointment && $appointment['userID'] === $_SESSION['user_id']) {
                    // Create rating
                    if (
                        $this->ratingModel->create([
                            'appointmentID' => $appointmentId,
                            'doctorID' => $appointment['DoctorID'],
                            'userID' => $_SESSION['user_id'],
                            'rating' => $rating,
                            'comment' => $comment
                        ])
                    ) {
                        $this->redirect('/clinicus/appointments');
                    } else {
                        $errors['system'] = 'Failed to submit rating';
                    }
                } else {
                    $errors['system'] = 'Invalid appointment';
                }
            }

            // If we get here, there were errors
            $this->render('ratings/create', [
                'errors' => $errors,
                'appointment' => $this->appointmentModel->read($appointmentId),
                'rating' => $rating,
                'comment' => $comment
            ]);
        } else {
            // Show rating form
            $this->render('ratings/create', [
                'appointment' => $this->appointmentModel->read($appointmentId)
            ]);
        }
    }

    public function view($doctorID)
    {
        $ratings = $this->ratingModel->getByDoctor($doctorID);
        $doctor = $this->doctorModel->read($doctorID);

        if ($doctor) {
            $this->render('ratings/view', [
                'ratings' => $ratings,
                'doctor' => $doctor
            ]);
        } else {
            $this->redirect('/clinicus/doctors');
        }
    }
}