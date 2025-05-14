<?php

require_once "config.php";

class CreateClass
{
    private $db;
    private $link;

    public function __construct()
    {
        $this->db = new DatabaseConnection();
        $this->link = $this->db->connectToDB();
    }

    public function insertRecord($tableName, $data, $userId = null): bool
    {
        $sql = '';
        $types = '';
        $params = [];

        switch ($tableName) {
            case 'usertype':
                $sql = 'INSERT INTO usertype (UserType, RoleId) VALUES (?, ?)';
                $types = 'si';
                $params = [$data['userType'], $data['roleId']];
                break;
            case 'users':
                $sql = 'INSERT INTO users (Name, Email, Password, Phone, DOB, Gender, Address, Role) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
                $types = 'sssssssi';
                $params = [$data['name'], $data['email'], password_hash($data['password'], PASSWORD_DEFAULT), $data['phone'], $data['dOB'], $data['gender'], $data['address'], $data['role']];
                break;
            case 'patients':
                $sql = 'INSERT INTO patients (UserId, HasHistory) VALUES (?, ?)';
                $types = 'ii';
                $params = [$data['userId'], $data['hasHistory']];
                break;
            case 'doctors':
                $sql = 'INSERT INTO doctors (UserId, Specialization, YearsOfExperience, Rating) VALUES (?, ?, ?, ?)';
                $types = 'isii';
                $params = [$data['userId'], $data['specialization'], $data['yearsOfExperience'], $data['rating']];
                break;
            case 'staff':
                $sql = 'INSERT INTO staff (UserId, RoleDescription) VALUES (?, ?)';
                $types = 'is';
                $params = [$data['userId'], $data['roleDescription']];
                break;
            case 'appointments':
                $sql = 'INSERT INTO appointments (PatientId, DoctorId, AppointmentDate, Status) VALUES (?, ?, ?, ?)';
                $types = 'iiss';
                $params = [$data['patientId'], $data['doctorId'], $data['appointmentDate'], $data['status']];
                break;
            case 'medical_history':
                $sql = 'INSERT INTO medical_history (PatientId, Diagnosis, Treatment, RecordDate) VALUES (?, ?, ?, ?)';
                $types = 'isss';
                $params = [$data['patientId'], $data['diagnosis'], $data['treatment'], $data['recordDate']];
                break;
            case 'medications':
                $sql = 'INSERT INTO medications (Name, Description, SideEffects) VALUES (?, ?, ?)';
                $types = 'sss';
                $params = [$data['name'], $data['description'], $data['sideEffects']];
                break;
            case 'prescriptions':
                $sql = 'INSERT INTO prescriptions (PatientId, DoctorId, MedicationId, Dosage, Instructions, DatePrescribed) VALUES (?, ?, ?, ?, ?, ?)';
                $types = 'iiisss';
                $params = [$data['patientId'], $data['doctorId'], $data['medicationId'], $data['dosage'], $data['instructions'], $data['datePrescribed']];
                break;
            case 'storage':
                $sql = 'INSERT INTO storage (Capacity, CurrentStock, LastRefilledDate) VALUES (?, ?, ?)';
                $types = 'iis';
                $params = [$data['capacity'], $data['currentStock'], $data['lastRefilledDate']];
                break;
            case 'audit_logs':
                $sql = 'INSERT INTO audit_logs (UserId, Action, Timestamp) VALUES (?, ?, ?)';
                $types = 'iss';
                $params = [$data['userId'], $data['action'], $data['timestamp']];
                break;
            default:
                throw new Exception("Invalid table name: $tableName");
        }

        if ($stmt = mysqli_prepare($this->link, $sql)) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);

                // Log the action to audit_logs
                $this->logAction($userId, "INSERT into $tableName");

                return true;
            } else {
                throw new Exception("Execution failed: " . mysqli_error($this->link));
            }
        } else {
            throw new Exception("Preparation failed: " . mysqli_error($this->link));
        }
    }

    private function logAction($userId, $action)
    {
        $timestamp = date('Y-m-d H:i:s');
        $userId = $userId ?? 1; // Use 1 if userId is not provided

        $sql = 'INSERT INTO audit_logs (UserId, Action, Timestamp) VALUES (?, ?, ?)';

        if ($stmt = mysqli_prepare($this->link, $sql)) {
            mysqli_stmt_bind_param($stmt, "iss", $userId, $action, $timestamp);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }
}
