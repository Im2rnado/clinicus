<?php

require_once "config.php";

class UpdateClass
{
    private $db;
    private $link;

    public function __construct()
    {
        $this->db = new DatabaseConnection();
        $this->link = $this->db->connectToDB();
    }

    public function updateRecord($tableName, $id, $data, $userId = null): bool
    {
        $sql = '';
        $types = '';
        $params = [];

        switch ($tableName) {
            case 'usertype':
                $sql = 'UPDATE usertype SET UserType=?, RoleId=? WHERE Id=?';
                $types = 'sii';
                $params = [$data['userType'], $data['roleId'], $id];
                break;
            case 'users':
                $sql = 'UPDATE users SET Name=?, Email=?, Password=?, Phone=?, DOB=?, Gender=?, Address=?, Role=? WHERE Id=?';
                $types = 'sssssssii';
                $params = [$data['name'], $data['email'], password_hash($data['password'], PASSWORD_DEFAULT), $data['phone'], $data['dob'], $data['gender'], $data['address'], $data['role'], $id];
                break;
            case 'patients':
                $sql = 'UPDATE patients SET UserId=?, HasHistory=? WHERE Id=?';
                $types = 'iii';
                $params = [$data['userId'], $data['hasHistory'], $id];
                break;
            case 'doctors':
                $sql = 'UPDATE doctors SET UserId=?, Specialization=?, YearsOfExperience=?, Rating=? WHERE Id=?';
                $types = 'isiii';
                $params = [$data['userId'], $data['specialization'], $data['yearsOfExperience'], $data['rating'], $id];
                break;
            case 'staff':
                $sql = 'UPDATE staff SET UserId=?, RoleDescription=? WHERE Id=?';
                $types = 'isi';
                $params = [$data['userId'], $data['roleDescription'], $id];
                break;
            case 'appointments':
                $sql = 'UPDATE appointments SET PatientId=?, DoctorId=?, AppointmentDate=?, Status=? WHERE Id=?';
                $types = 'iissi';
                $params = [$data['patientId'], $data['doctorId'], $data['appointmentDate'], $data['status'], $id];
                break;
            case 'medical_history':
                $sql = 'UPDATE medical_history SET PatientId=?, Diagnosis=?, Treatment=?, RecordDate=? WHERE Id=?';
                $types = 'isssi';
                $params = [$data['patientId'], $data['diagnosis'], $data['treatment'], $data['recordDate'], $id];
                break;
            case 'medications':
                $sql = 'UPDATE medications SET Name=?, Description=?, SideEffects=? WHERE Id=?';
                $types = 'sssi';
                $params = [$data['name'], $data['description'], $data['sideEffects'], $id];
                break;
            case 'prescriptions':
                $sql = 'UPDATE prescriptions SET PatientId=?, DoctorId=?, MedicationId=?, Dosage=?, Instructions=?, DatePrescribed=? WHERE Id=?';
                $types = 'iiisssi';
                $params = [$data['patientId'], $data['doctorId'], $data['medicationId'], $data['dosage'], $data['instructions'], $data['datePrescribed'], $id];
                break;
            case 'storage':
                $sql = 'UPDATE storage SET Capacity=?, CurrentStock=?, LastRefilledDate=? WHERE Id=?';
                $types = 'iisi';
                $params = [$data['capacity'], $data['currentStock'], $data['lastRefilledDate'], $id];
                break;
            case 'audit_logs':
                $sql = 'UPDATE audit_logs SET UserId=?, Action=?, Timestamp=? WHERE Id=?';
                $types = 'issi';
                $params = [$data['userId'], $data['action'], $data['timestamp'], $id];
                break;
            default:
                throw new Exception("Invalid table name: $tableName");
        }

        if ($stmt = mysqli_prepare($this->link, $sql)) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);

                // Log the action to audit_logs
                $this->logAction($userId, "UPDATE in $tableName with ID: $id");

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
