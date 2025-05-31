<?php
namespace Model\entities;

require_once __DIR__ . "/../abstract/AbstractModel.php";

use Model\abstract\AbstractModel;

class Payment extends AbstractModel
{
    public $ID;
    public $appointmentID;
    public $userID;
    public $amount;
    public $paymentMethod;
    public $status;
    public $transactionID;
    public $createdAt;
    public $updatedAt;

    public function __construct($db)
    {
        parent::__construct($db);
        $this->tableName = 'Payment';
    }

    public function create($data)
    {
        try {
            $sql = "INSERT INTO {$this->tableName} (appointmentID, userID, amount, paymentMethod, status, transactionID, createdAt, updatedAt) 
                    VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())";
            $stmt = $this->conn->prepare($sql);

            return $stmt->execute([
                $data['appointmentID'],
                $data['userID'],
                $data['amount'],
                $data['paymentMethod'],
                $data['status'],
                $data['transactionID']
            ]);
        } catch (\Exception $e) {
            error_log('Exception in Payment::create: ' . $e->getMessage());
            return false;
        }
    }

    public function read($id)
    {
        $stmt = $this->conn->prepare("
            SELECT p.*, 
                   CONCAT(u.FirstName, ' ', u.LastName) as patientName,
                   CONCAT(d.FirstName, ' ', d.LastName) as doctorName,
                   a.appointmentDate
            FROM Payment p
            JOIN Users u ON p.userID = u.userID
            JOIN Appointment a ON p.appointmentID = a.ID
            JOIN Doctors d ON a.DoctorID = d.ID
            WHERE p.ID = ?
        ");

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $payment = $result->fetch_assoc();
        $stmt->close();
        return $payment;
    }

    public function update($id, $data)
    {
        $stmt = $this->conn->prepare("
            UPDATE Payment 
            SET status = ?, transactionID = ?, updatedAt = NOW()
            WHERE ID = ?
        ");

        $stmt->bind_param("ssi", $data['status'], $data['transactionID'], $id);
        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
            // Get appointment ID for updating status
            $payment = $this->read($id);
            if ($payment) {
                $this->updateAppointmentStatus($payment['appointmentID']);
            }
        }

        return $result;
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM Payment WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function getPaymentsByUser($userId)
    {
        $stmt = $this->conn->prepare("
            SELECT p.*, 
                   CONCAT(d.FirstName, ' ', d.LastName) as doctorName,
                   a.appointmentDate,
                   DATE_FORMAT(p.createdAt, '%M %d, %Y') as formattedDate
            FROM Payment p
            JOIN Appointment a ON p.appointmentID = a.ID
            JOIN Doctors d ON a.DoctorID = d.ID
            WHERE p.userID = ?
            ORDER BY p.createdAt DESC
        ");

        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $payments = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $payments;
    }

    public function getPaymentsByAppointment($appointmentId)
    {
        $stmt = $this->conn->prepare("
            SELECT * FROM Payment 
            WHERE appointmentID = ?
        ");

        $stmt->bind_param("i", $appointmentId);
        $stmt->execute();
        $result = $stmt->get_result();
        $payment = $result->fetch_assoc();
        $stmt->close();
        return $payment;
    }

    private function updateAppointmentStatus($appointmentId)
    {
        $stmt = $this->conn->prepare("
            UPDATE Appointment 
            SET status = 1, updatedAt = NOW()
            WHERE ID = ?
        ");

        $stmt->bind_param("i", $appointmentId);
        $stmt->execute();
        $stmt->close();
    }

    public function getPaymentMethods()
    {
        $sql = "SELECT ID, name FROM Payment_Methods";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $methods = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $methods;
    }

    public function getPaymentOptions($type)
    {
        $sql = "SELECT ID, name FROM Options WHERE Type = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $type);
        $stmt->execute();
        $result = $stmt->get_result();
        $options = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $options;
    }

    public function getPaymentStatuses()
    {
        return [
            'pending' => 'Pending',
            'completed' => 'Completed',
            'failed' => 'Failed',
            'refunded' => 'Refunded'
        ];
    }

    public function getPaymentsByUserId($userId)
    {
        $sql = "SELECT p.*, a.appointmentDate, d.FirstName as doctorFirstName, d.LastName as doctorLastName 
                FROM Payment p 
                JOIN Appointment a ON p.appointmentID = a.ID 
                JOIN Doctors d ON a.DoctorID = d.ID 
                WHERE p.userID = ? 
                ORDER BY p.createdAt DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $payments = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $payments;
    }

    public function getPaymentByAppointmentId($appointmentId)
    {
        $sql = "SELECT * FROM Payment WHERE appointmentID = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $appointmentId);
        $stmt->execute();
        $result = $stmt->get_result();
        $payment = $result->fetch_assoc();
        $stmt->close();
        return $payment;
    }

    public function getPaymentMethod($paymentMethodId)
    {
        $sql = "SELECT * FROM Payment_Methods WHERE ID = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $paymentMethodId);
        $stmt->execute();
    }
}