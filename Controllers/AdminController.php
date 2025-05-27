<?php

require_once "../Model/config.php";
require_once "../Model/autoload.php";

use Model\entities\ModelFactory;

class AdminController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireRole(1);
        $this->db = (new DatabaseConnection())->connectToDB();
    }

    protected function render($view, $data = [])
    {
        // Extract data to make variables available in view
        extract($data);

        // Start output buffering
        ob_start();

        // Include the view file
        require_once "views/admin/{$view}.php";

        // Get the contents of the buffer
        $content = ob_get_clean();

        // If this is an AJAX request, just return the content
        if (
            !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
        ) {
            echo $content;
            return;
        }

        // Otherwise, wrap in admin layout
        require_once "admin/layout.php";
    }

    public function dashboard()
    {
        $this->render('dashboard', [
            'title' => 'Admin Dashboard'
        ]);
    }

    public function users()
    {
        $this->render('users', [
            'title' => 'Manage Users'
        ]);
    }

    public function appointments()
    {
        $this->render('appointments', [
            'title' => 'Manage Appointments'
        ]);
    }

    public function doctors()
    {
        $this->render('doctors', [
            'title' => 'Manage Doctors'
        ]);
    }

    public function patients()
    {
        $this->render('patients', [
            'title' => 'Manage Patients'
        ]);
    }

    public function manageTable($tableName)
    {
        try {
            $model = ModelFactory::getModelInstance($tableName, $this->db);
            $records = $model->readAll();

            // Get column names (from first record)
            $columns = [];
            if (!empty($records)) {
                $columns = array_keys($records[0]);
            }

            $this->render('table', [
                'title' => 'Edit ' . $tableName,
                'tableName' => $tableName,
                'records' => $records,
                'columns' => $columns
            ]);

        } catch (Exception $e) {
            echo 'Error has occurred: ' . $e->getMessage();
        }
    }

    private function getTotalPatients()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM Patients");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    private function getTotalDoctors()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM Doctors");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    private function getTodayAppointments()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM Appointment WHERE DATE(appointmentDate) = CURDATE()");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    private function getTotalRevenue()
    {
        $stmt = $this->db->prepare("SELECT SUM(totalPrice) as total FROM Appointment_Details");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }

    private function getRecentAppointments()
    {
        $stmt = $this->db->prepare("
            SELECT 
                a.ID,
                CONCAT(u1.FirstName, ' ', u1.LastName) as patientName,
                CONCAT(u2.FirstName, ' ', u2.LastName) as doctorName,
                a.appointmentDate as date,
                CASE 
                    WHEN a.status = 0 THEN 'Pending'
                    WHEN a.status = 1 THEN 'Confirmed'
                    WHEN a.status = 2 THEN 'Cancelled'
                    ELSE 'Completed'
                END as status,
                CASE 
                    WHEN a.status = 0 THEN 'warning'
                    WHEN a.status = 1 THEN 'success'
                    WHEN a.status = 2 THEN 'danger'
                    ELSE 'info'
                END as statusColor
            FROM Appointment a
            JOIN Users u1 ON a.userID = u1.userID
            JOIN Doctors d ON a.DoctorID = d.ID
            JOIN Users u2 ON d.userID = u2.userID
            ORDER BY a.appointmentDate DESC
            LIMIT 5
        ");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function index()
    {
        $this->render('admin/dashboard', [
            'title' => 'Admin Dashboard'
        ]);
    }
}
