<?php
namespace Model\entities;

include_once __DIR__ . "/../interfaces/iAppointmentActions.php";

use Model\interfaces\iAppointmentActions;

class Appointment implements iAppointmentActions
{
    protected $db;
    protected $tableName = 'Appointment';

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Create a new appointment
     * 
     * @param array $data Appointment data
     * @return bool Success status
     */
    public function create($data)
    {
        try {
            $sql = "INSERT INTO {$this->tableName} (DoctorID, userID, appointmentDate, reason, status, createdAt, updatedAt) 
                    VALUES (?, ?, ?, ?, ?, NOW(), NOW())";
            $stmt = $this->db->prepare($sql);

            $result = $stmt->execute([
                $data['DoctorID'],
                $data['userID'],
                $data['appointmentDate'],
                $data['reason'],
                $data['status']
            ]);

            return $result;
        } catch (\Exception $e) {
            error_log('Exception in Appointment::create: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Read appointment by ID
     * 
     * @param int $id Appointment ID
     * @return array|bool Appointment data or false if not found
     */
    public function read($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM Appointment WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $appointment = $result->fetch_assoc();
        $stmt->close();
        return $appointment;
    }

    /**
     * Update appointment
     * 
     * @param int $id Appointment ID
     * @param array $data Updated appointment data
     * @return bool Success status
     */
    public function update($id, $data)
    {
        $updates = [];
        $params = [];

        foreach ($data as $key => $value) {
            $updates[] = "$key = ?";
            $params[] = $value;
        }

        $updates[] = "updatedAt = NOW()";
        $params[] = $id;

        $sql = "UPDATE {$this->tableName} SET " . implode(", ", $updates) . " WHERE ID = ?";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute($params);
    }

    /**
     * Delete appointment
     * 
     * @param int $id Appointment ID
     * @return bool Success status
     */
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->tableName} WHERE ID = ?";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([$id]);
    }

    /**
     * Get all appointments
     * 
     * @return array All appointments
     */
    public function readAll()
    {
        $sql = "SELECT a.*, 
                CONCAT(u.FirstName, ' ', u.LastName) as patientName,
                CONCAT(d.FirstName, ' ', d.LastName) as doctorName,
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
                FROM {$this->tableName} a
                JOIN Users u ON a.userID = u.userID
                JOIN Doctors doc ON a.DoctorID = doc.ID
                JOIN Users d ON doc.userID = d.userID
                ORDER BY a.appointmentDate DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $appointments = [];
        while ($row = $result->fetch_assoc()) {
            $appointments[] = $row;
        }
        $stmt->close();
        return $appointments;
    }

    /**
     * Get appointments by user
     * 
     * @param int $userId User ID
     * @return array User's appointments
     */
    public function getAppointmentsByUser($userId)
    {
        $sql = "SELECT a.*, 
                CONCAT(u.FirstName, ' ', u.LastName) as doctorName,
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
                FROM {$this->tableName} a
                JOIN Doctors d ON a.DoctorID = d.ID
                JOIN Users u ON a.userID = u.userID
                WHERE a.userID = ?
                ORDER BY a.appointmentDate DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);

        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Check if a time slot is available
     * 
     * @param int $doctorId Doctor ID
     * @param string $date Date
     * @param string $time Time
     * @return bool True if available, false if not
     */
    public function checkAvailability($doctorId, $date, $time)
    {
        $sql = "SELECT COUNT(*) as count 
                FROM {$this->tableName} 
                WHERE DoctorID = ? 
                AND DATE(appointmentDate) = ? 
                AND TIME(appointmentDate) = ? 
                AND status != 2";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$doctorId, $date, $time]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result['count'] == 0;
    }

    /**
     * Get appointment by ID
     * 
     * @param int $appointmentId
     * @return array|bool Appointment data or false if not found
     */
    public function getAppointmentById($appointmentId)
    {
        $sql = "SELECT a.*, 
                CONCAT(u.FirstName, ' ', u.LastName) as PatientName,
                CONCAT(du.FirstName, ' ', du.LastName) as DoctorName
                FROM {$this->tableName} a
                JOIN Users u ON a.userID = u.userID
                JOIN Doctors d ON a.DoctorID = d.ID
                JOIN Users du ON d.userID = du.userID
                WHERE a.ID = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$appointmentId]);

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Schedule a new appointment
     * 
     * @param int $doctorId
     * @param int $patientId
     * @param string $appointmentDate
     * @param int $status
     * @return int|bool New appointment ID or false on failure
     */
    public function scheduleAppointment($doctorId, $patientId, $appointmentDate, $status = 0)
    {
        // First, get the patient's user ID
        $patientSql = "SELECT userID FROM Patients WHERE ID = ?";
        $patientStmt = $this->db->prepare($patientSql);
        $patientStmt->execute([$patientId]);
        $patientData = $patientStmt->fetch(\PDO::FETCH_ASSOC);

        if (!$patientData) {
            return false;
        }

        // Create the appointment
        $sql = "INSERT INTO {$this->tableName} (DoctorID, userID, appointmentDate, status) 
                VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            $doctorId,
            $patientData['userID'],
            $appointmentDate,
            $status
        ]);

        if ($result) {
            return $this->db->lastInsertId();
        }

        return false;
    }

    /**
     * Cancel an appointment
     * 
     * @param int $appointmentId
     * @return bool Success status
     */
    public function cancelAppointment($appointmentId)
    {
        // Cancel means setting status to canceled (2)
        return $this->updateAppointmentStatus($appointmentId, 2);
    }

    /**
     * Reschedule an appointment
     * 
     * @param int $appointmentId
     * @param string $newAppointmentDate
     * @return bool Success status
     */
    public function rescheduleAppointment($appointmentId, $newAppointmentDate)
    {
        $sql = "UPDATE {$this->tableName} SET appointmentDate = ? WHERE ID = ?";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([$newAppointmentDate, $appointmentId]);
    }

    /**
     * Update appointment status
     * 
     * @param int $appointmentId
     * @param int $newStatus
     * @return bool Success status
     */
    public function updateAppointmentStatus($appointmentId, $newStatus)
    {
        $sql = "UPDATE {$this->tableName} SET status = ? WHERE ID = ?";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([$newStatus, $appointmentId]);
    }

    /**
     * Get appointments by doctor
     * 
     * @param int $doctorId
     * @return array Appointments for the doctor
     */
    public function getAppointmentsByDoctor($doctorId)
    {
        $sql = "SELECT a.*, 
                CONCAT(u.FirstName, ' ', u.LastName) as PatientName
                FROM {$this->tableName} a
                JOIN Users u ON a.userID = u.userID
                WHERE a.DoctorID = ?
                ORDER BY a.appointmentDate DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$doctorId]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get appointments by patient
     * 
     * @param int $patientId
     * @return array Appointments for the patient
     */
    public function getAppointmentsByPatient($patientId)
    {
        // First, get the patient's user ID
        $patientSql = "SELECT userID FROM Patients WHERE ID = ?";
        $patientStmt = $this->db->prepare($patientSql);
        $patientStmt->execute([$patientId]);
        $patientData = $patientStmt->fetch(\PDO::FETCH_ASSOC);

        if (!$patientData) {
            return [];
        }

        $sql = "SELECT a.*, 
                CONCAT(du.FirstName, ' ', du.LastName) as DoctorName
                FROM {$this->tableName} a
                JOIN Doctors d ON a.DoctorID = d.ID
                JOIN Users du ON d.userID = du.userID
                WHERE a.userID = ?
                ORDER BY a.appointmentDate DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$patientData['userID']]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get appointments by date range
     * 
     * @param string $startDate Start date (Y-m-d)
     * @param string $endDate End date (Y-m-d)
     * @return array Appointments within the date range
     */
    public function getAppointmentsByDateRange($startDate, $endDate)
    {
        $sql = "SELECT a.*, 
                CONCAT(u.FirstName, ' ', u.LastName) as PatientName,
                CONCAT(du.FirstName, ' ', du.LastName) as DoctorName
                FROM {$this->tableName} a
                JOIN Users u ON a.userID = u.userID
                JOIN Doctors d ON a.DoctorID = d.ID
                JOIN Users du ON d.userID = du.userID
                WHERE DATE(a.appointmentDate) BETWEEN ? AND ?
                ORDER BY a.appointmentDate";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$startDate, $endDate]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get appointment details
     * 
     * @param int $appointmentId
     * @return array Appointment details
     */
    public function getAppointmentDetails($appointmentId)
    {
        $sql = "SELECT ad.*, s.name as ServiceName
                FROM Appointment_Details ad
                JOIN Services s ON ad.serviceID = s.ID
                WHERE ad.appointmentID = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$appointmentId]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Add service to appointment
     * 
     * @param int $appointmentId
     * @param int $serviceId
     * @param int $quantity
     * @param float $pricePerUnit
     * @return bool Success status
     */
    public function addServiceToAppointment($appointmentId, $serviceId, $quantity = 1, $pricePerUnit = null)
    {
        // If price not provided, get from service
        if ($pricePerUnit === null) {
            $serviceSql = "SELECT price FROM Services WHERE ID = ?";
            $serviceStmt = $this->db->prepare($serviceSql);
            $serviceStmt->execute([$serviceId]);
            $serviceData = $serviceStmt->fetch(\PDO::FETCH_ASSOC);

            if (!$serviceData) {
                return false;
            }

            $pricePerUnit = $serviceData['price'];
        }

        // Calculate total price
        $totalPrice = $pricePerUnit * $quantity;

        // Add service to appointment
        $sql = "INSERT INTO Appointment_Details (appointmentID, serviceID, quantity, pricePerUnit, totalPrice) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $appointmentId,
            $serviceId,
            $quantity,
            $pricePerUnit,
            $totalPrice
        ]);
    }

    /**
     * Remove service from appointment
     * 
     * @param int $detailId
     * @return bool Success status
     */
    public function removeServiceFromAppointment($detailId)
    {
        $sql = "DELETE FROM Appointment_Details WHERE ID = ?";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([$detailId]);
    }

    /**
     * Get today's appointments
     * 
     * @return array Today's appointments
     */
    public function getTodayAppointments()
    {
        $today = date('Y-m-d');

        $sql = "SELECT a.*, 
                CONCAT(u.FirstName, ' ', u.LastName) as PatientName,
                CONCAT(du.FirstName, ' ', du.LastName) as DoctorName
                FROM {$this->tableName} a
                JOIN Users u ON a.userID = u.userID
                JOIN Doctors d ON a.DoctorID = d.ID
                JOIN Users du ON d.userID = du.userID
                WHERE DATE(a.appointmentDate) = ?
                ORDER BY a.appointmentDate";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$today]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get appointment statistics
     * 
     * @return array Appointment statistics
     */
    public function getAppointmentStatistics()
    {
        $today = date('Y-m-d');
        $thisWeekStart = date('Y-m-d', strtotime('monday this week'));
        $thisWeekEnd = date('Y-m-d', strtotime('sunday this week'));
        $thisMonthStart = date('Y-m-01');
        $thisMonthEnd = date('Y-m-t');

        // Today's appointments
        $todaySql = "SELECT COUNT(*) as count FROM {$this->tableName} WHERE DATE(appointmentDate) = ?";
        $todayStmt = $this->db->prepare($todaySql);
        $todayStmt->execute([$today]);
        $todayCount = $todayStmt->fetch(\PDO::FETCH_ASSOC)['count'];

        // This week's appointments
        $weekSql = "SELECT COUNT(*) as count FROM {$this->tableName} WHERE DATE(appointmentDate) BETWEEN ? AND ?";
        $weekStmt = $this->db->prepare($weekSql);
        $weekStmt->execute([$thisWeekStart, $thisWeekEnd]);
        $weekCount = $weekStmt->fetch(\PDO::FETCH_ASSOC)['count'];

        // This month's appointments
        $monthSql = "SELECT COUNT(*) as count FROM {$this->tableName} WHERE DATE(appointmentDate) BETWEEN ? AND ?";
        $monthStmt = $this->db->prepare($monthSql);
        $monthStmt->execute([$thisMonthStart, $thisMonthEnd]);
        $monthCount = $monthStmt->fetch(\PDO::FETCH_ASSOC)['count'];

        // Status counts
        $statusSql = "SELECT status, COUNT(*) as count FROM {$this->tableName} GROUP BY status";
        $statusStmt = $this->db->prepare($statusSql);
        $statusStmt->execute();
        $statusCounts = $statusStmt->fetchAll(\PDO::FETCH_ASSOC);

        // Format status counts
        $statusData = [
            'scheduled' => 0,
            'completed' => 0,
            'canceled' => 0,
            'noshow' => 0
        ];

        foreach ($statusCounts as $status) {
            switch ($status['status']) {
                case 0:
                    $statusData['scheduled'] = $status['count'];
                    break;
                case 1:
                    $statusData['completed'] = $status['count'];
                    break;
                case 2:
                    $statusData['canceled'] = $status['count'];
                    break;
                case 3:
                    $statusData['noshow'] = $status['count'];
                    break;
            }
        }

        return [
            'today' => $todayCount,
            'week' => $weekCount,
            'month' => $monthCount,
            'status' => $statusData
        ];
    }

    public function getUpcomingAppointments($patientId)
    {
        $stmt = $this->db->prepare("
            SELECT 
                a.ID,
                a.appointmentDate,
                a.status,
                CONCAT(d.FirstName, ' ', d.LastName) as doctorName,
                s.Specialization as specialization,
                ad.totalPrice
            FROM Appointment a
            JOIN Users d ON a.DoctorID = d.userID
            JOIN Doctors doc ON a.DoctorID = doc.ID
            JOIN doctor_types s ON doc.doctorType = s.ID
            LEFT JOIN Appointment_Details ad ON a.ID = ad.appointmentID
            WHERE a.userID = ? 
            AND a.appointmentDate >= CURDATE()
            AND a.status != 2
            ORDER BY a.appointmentDate ASC
        ");
        $stmt->bind_param("i", $patientId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getAllAppointments($patientId)
    {
        $stmt = $this->db->prepare("
            SELECT 
                a.ID,
                a.appointmentDate,
                a.status,
                CONCAT(d.FirstName, ' ', d.LastName) as doctorName,
                s.Specialization as specialization,
                ad.totalPrice
            FROM Appointment a
            JOIN Doctors doc ON a.DoctorID = doc.ID
            JOIN Users d ON doc.userID = d.userID
            JOIN doctor_types s ON doc.doctorType = s.ID
            LEFT JOIN Appointment_Details ad ON a.ID = ad.appointmentID
            WHERE a.userID = ?
            ORDER BY a.appointmentDate DESC
        ");
        $stmt->bind_param("i", $patientId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function cancel($id, $userId)
    {
        $stmt = $this->db->prepare("
            UPDATE Appointment 
            SET status = 2
            WHERE ID = ? AND userID = ?
        ");
        $stmt->bind_param("ii", $id, $userId);
        return $stmt->execute();
    }

    public function getCount()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM Appointment");
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_assoc()['COUNT(*)'];
    }

    public function getAppointmentsByDoctorAndDate($doctorId, $date)
    {
        $stmt = $this->db->prepare("
            SELECT 
                a.*,
                CONCAT(u.FirstName, ' ', u.LastName) as patientName,
                a.status,
                p.status as paymentStatus
            FROM Appointment a
            JOIN Users u ON a.userID = u.userID
            LEFT JOIN Payment p ON a.ID = p.appointmentID
            WHERE a.DoctorID = ? 
            AND DATE(a.appointmentDate) = ?
            ORDER BY a.appointmentDate ASC
        ");
        $stmt->bind_param("is", $doctorId, $date);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getAllAppointmentsByDoctor($doctorId)
    {
        $stmt = $this->db->prepare("
            SELECT 
                a.*,
                CONCAT(u.FirstName, ' ', u.LastName) as patientName,
                a.status,
                COALESCE(p.status, 'unpaid') as paymentStatus
            FROM Appointment a
            JOIN Users u ON a.userID = u.userID
            LEFT JOIN Payment p ON a.ID = p.appointmentID
            WHERE a.DoctorID = ?
            ORDER BY a.appointmentDate DESC
        ");
        $stmt->bind_param("i", $doctorId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getUpcomingAppointmentsByDoctor($doctorId)
    {
        $stmt = $this->db->prepare("
            SELECT 
                a.*,
                CONCAT(u.FirstName, ' ', u.LastName) as patientName,
                a.status,
                p.status as paymentStatus
            FROM Appointment a
            JOIN Users u ON a.userID = u.userID
            LEFT JOIN Payment p ON a.ID = p.appointmentID
            WHERE a.DoctorID = ? 
            AND a.appointmentDate > NOW()
            AND a.status != 2
            ORDER BY a.appointmentDate ASC
        ");
        $stmt->bind_param("i", $doctorId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getTotalAppointmentsByDoctor($doctorId)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as total
            FROM Appointment
            WHERE DoctorID = ?
        ");
        $stmt->bind_param("i", $doctorId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['total'];
    }

    public function updateStatus($id, $status)
    {
        $stmt = $this->db->prepare("UPDATE Appointment SET status = ? WHERE ID = ?");
        $stmt->bind_param("ii", $status, $id);
        return $stmt->execute();
    }
}
