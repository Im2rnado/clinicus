<?php
require_once '../Model/read.php';

$readModel = new ReadClass();

// Get some statistics for the dashboard
try {
    $users = $readModel->readAll('users');
    $doctors = $readModel->readAll('doctors');
    $patients = $readModel->readAll('patients');
    $appointments = $readModel->readAll('appointments');
    $prescriptions = $readModel->readAll('prescriptions');
    $medications = $readModel->readAll('medications');
    $staff = $readModel->readAll('staff');
    $medicalHistory = $readModel->readAll('medical_history');
    $auditLogs = $readModel->readAll('audit_logs');

    $stats = [
        'totalUsers' => count($users),
        'totalDoctors' => count($doctors),
        'totalPatients' => count($patients),
        'totalAppointments' => count($appointments),
        'totalPrescriptions' => count($prescriptions),
        'totalMedications' => count($medications),
        'totalStaff' => count($staff),
        'totalMedicalHistory' => count($medicalHistory),
        'totalAuditLogs' => count($auditLogs)
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
                    <a href="/clinicus/admin/users" class="btn btn-primary">Manage Users</a>
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
                    <a href="/clinicus/admin/usertype" class="btn btn-primary">Manage User Types</a>
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
                    <a href="/clinicus/admin/doctors" class="btn btn-primary">Manage Doctors</a>
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
                    <a href="/clinicus/admin/patients" class="btn btn-primary">Manage Patients</a>
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
                    <a href="/clinicus/admin/appointments" class="btn btn-primary">Manage
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
                    <a href="/clinicus/admin/medical_history" class="btn btn-primary">Manage Medical
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
                    <a href="/clinicus/admin/medications" class="btn btn-primary">Manage Medications</a>
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
                    <a href="/clinicus/admin/prescriptions" class="btn btn-primary">Manage
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
                    <a href="/clinicus/admin/staff" class="btn btn-primary">Manage Staff</a>
                </div>
            </div>
        </div>
    </div>
</div>