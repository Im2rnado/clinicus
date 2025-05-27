<?php
require_once '../Model/config.php';
require_once '../Model/autoload.php';

use Model\entities\ModelFactory;

$db = (new DatabaseConnection())->connectToDB();

// Get some statistics for the dashboard
try {
    $userModel = ModelFactory::getModelInstance('users', $db);
    $doctorModel = ModelFactory::getModelInstance('doctors', $db);
    $patientModel = ModelFactory::getModelInstance('patients', $db);
    $appointmentModel = ModelFactory::getModelInstance('appointments', $db);
    $prescriptionModel = ModelFactory::getModelInstance('prescriptions', $db);
    $medicationModel = ModelFactory::getModelInstance('medications', $db);
    $staffModel = ModelFactory::getModelInstance('staff', $db);
    $medicalHistoryModel = ModelFactory::getModelInstance('medical_history', $db);
    $auditLogModel = ModelFactory::getModelInstance('audit_logs', $db);

    $stats = [
        'totalUsers' => count($userModel->readAll()),
        'totalDoctors' => count($doctorModel->readAll()),
        'totalPatients' => count($patientModel->readAll()),
        'totalAppointments' => count($appointmentModel->readAll()),
        'totalPrescriptions' => count($prescriptionModel->readAll()),
        'totalMedications' => count($medicationModel->readAll()),
        'totalStaff' => count($staffModel->readAll()),
        'totalMedicalHistory' => count($medicalHistoryModel->readAll()),
        'totalAuditLogs' => count($auditLogModel->readAll())
    ];
} catch (Exception $e) {
    $stats = [
        'totalUsers' => '0',
        'totalDoctors' => '0',
        'totalPatients' => '0',
        'totalAppointments' => '0',
        'totalPrescriptions' => '0',
        'totalMedications' => '0',
        'totalStaff' => '0',
        'totalMedicalHistory' => '0',
        'totalAuditLogs' => '?'
    ];
}
?>

<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h1>Admin Dashboard</h1>
            <p class="lead">Welcome to the Clinicus admin panel</p>
            <hr>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card bg-light">
                <div class="card-body">
                    <h5 class="card-title mb-3">System Overview</h5>
                    <div class="row text-center">
                        <div class="col-md-2 col-sm-4 mb-3">
                            <div class="bg-white p-3 rounded shadow-sm">
                                <i class="bi bi-people text-primary fs-3"></i>
                                <h3 class="mb-1"><?php echo $stats['totalUsers']; ?></h3>
                                <p class="small text-muted mb-0">Total Users</p>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-4 mb-3">
                            <div class="bg-white p-3 rounded shadow-sm">
                                <i class="bi bi-clipboard2-pulse text-primary fs-3"></i>
                                <h3 class="mb-1"><?php echo $stats['totalDoctors']; ?></h3>
                                <p class="small text-muted mb-0">Doctors</p>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-4 mb-3">
                            <div class="bg-white p-3 rounded shadow-sm">
                                <i class="bi bi-person-plus text-primary fs-3"></i>
                                <h3 class="mb-1"><?php echo $stats['totalPatients']; ?></h3>
                                <p class="small text-muted mb-0">Patients</p>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-4 mb-3">
                            <div class="bg-white p-3 rounded shadow-sm">
                                <i class="bi bi-calendar-check text-primary fs-3"></i>
                                <h3 class="mb-1"><?php echo $stats['totalAppointments']; ?></h3>
                                <p class="small text-muted mb-0">Appointments</p>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-4 mb-3">
                            <div class="bg-white p-3 rounded shadow-sm">
                                <i class="bi bi-file-earmark-medical text-primary fs-3"></i>
                                <h3 class="mb-1"><?php echo $stats['totalPrescriptions']; ?></h3>
                                <p class="small text-muted mb-0">Prescriptions</p>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-4 mb-3">
                            <div class="bg-white p-3 rounded shadow-sm">
                                <i class="bi bi-file-earmark-medical text-primary fs-3"></i>
                                <h3 class="mb-1"><?php echo $stats['totalMedications']; ?></h3>
                                <p class="small text-muted mb-0">Medications</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- First row of modules -->
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="bi bi-people text-primary fs-4"></i>
                        </div>
                        <h5 class="card-title mb-0">Users</h5>
                    </div>
                    <p class="card-text">Manage system users, including administrators, staff, doctors, and patients.
                    </p>
                    <a href="./admin/users" class="btn btn-primary">Manage Users</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="bi bi-person-badge text-primary fs-4"></i>
                        </div>
                        <h5 class="card-title mb-0">User Types</h5>
                    </div>
                    <p class="card-text">Manage different types of users and their roles in the system.</p>
                    <a href="./admin/usertype" class="btn btn-primary">Manage User Types</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="bi bi-clipboard2-pulse text-primary fs-4"></i>
                        </div>
                        <h5 class="card-title mb-0">Doctors</h5>
                    </div>
                    <p class="card-text">Add, edit, or remove doctors and their specializations.</p>
                    <a href="./admin/doctors" class="btn btn-primary">Manage Doctors</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Second row of modules -->
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="bi bi-person-plus text-primary fs-4"></i>
                        </div>
                        <h5 class="card-title mb-0">Patients</h5>
                    </div>
                    <p class="card-text">Manage patient records and their personal information.</p>
                    <a href="./admin/patients" class="btn btn-primary">Manage Patients</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="bi bi-calendar-check text-primary fs-4"></i>
                        </div>
                        <h5 class="card-title mb-0">Appointments</h5>
                    </div>
                    <p class="card-text">Schedule and manage patient appointments with doctors.</p>
                    <a href="./admin/appointments" class="btn btn-primary">Manage
                        Appointments</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="bi bi-journal-medical text-primary fs-4"></i>
                        </div>
                        <h5 class="card-title mb-0">Medical History</h5>
                    </div>
                    <p class="card-text">View and update the patients' medical history records.</p>
                    <a href="./admin/medical_history" class="btn btn-primary">Manage Medical
                        History</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Third row of modules -->
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="bi bi-file-earmark-medical text-primary fs-4"></i>
                        </div>
                        <h5 class="card-title mb-0">Medications</h5>
                    </div>
                    <p class="card-text">Add, edit, or remove medications.</p>
                    <a href="./admin/medications" class="btn btn-primary">Manage Medications</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="bi bi-file-earmark-medical text-primary fs-4"></i>
                        </div>
                        <h5 class="card-title mb-0">Prescriptions</h5>
                    </div>
                    <p class="card-text">Manage prescriptions issued by doctors to patients.</p>
                    <a href="./admin/prescriptions" class="btn btn-primary">Manage
                        Prescriptions</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="bi bi-person-workspace text-primary fs-4"></i>
                        </div>
                        <h5 class="card-title mb-0">Staff</h5>
                    </div>
                    <p class="card-text">Manage clinic staff members and their roles.</p>
                    <a href="./admin/staff" class="btn btn-primary">Manage Staff</a>
                </div>
            </div>
        </div>
    </div>
</div>