<?php
namespace Model\entities;

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
        $stmt = $this->conn->prepare("
            INSERT INTO Payment (appointmentID, userID, amount, paymentMethod, status, transactionID, createdAt, updatedAt)
            VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");

        $stmt->bind_param(
            "iidsss",
            $data['appointmentID'],
            $data['userID'],
            $data['amount'],
            $data['paymentMethod'],
            $data['status'],
            $data['transactionID']
        );

        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
            // Update appointment status to paid
            $this->updateAppointmentStatus($data['appointmentID']);
        }

        return $result;
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
        return [
            'credit_card' => 'Credit Card',
            'debit_card' => 'Debit Card',
            'paypal' => 'PayPal',
            'bank_transfer' => 'Bank Transfer'
        ];
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
}