<?php
$title = "Admin Dashboard - Clinicus";
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
    $staffModel = ModelFactory::getModelInstance('staff', $db);
    $medicalHistoryModel = ModelFactory::getModelInstance('medical_history', $db);

    // Get counts using mysqli style
    $stats = [
        'totalUsers' => $userModel->getCount(),
        'totalDoctors' => $doctorModel->getCount(),
        'totalPatients' => $patientModel->getCount(),
        'totalAppointments' => $appointmentModel->getCount(),
        'totalStaff' => $staffModel->getCount(),
        'totalMedicalHistory' => $medicalHistoryModel->getCount(),
    ];
} catch (Exception $e) {
    error_log('Dashboard Error: ' . $e->getMessage());
    $stats = [
        'totalUsers' => '0',
        'totalDoctors' => '0',
        'totalPatients' => '0',
        'totalAppointments' => '0',
        'totalStaff' => '0',
        'totalMedicalHistory' => '0',
    ];
}
?>

<div class="container py-4">
    <div class="row">
        <!-- System Overview -->
        <div class="col-md-6 mb-4">
            <div class="box">
                <h3 class="mb-4">System Overview</h3>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th>Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Total Users</td>
                                <td><?php echo $stats['totalUsers']; ?></td>
                            </tr>
                            <tr>
                                <td>Doctors</td>
                                <td><?php echo $stats['totalDoctors']; ?></td>
                            </tr>
                            <tr>
                                <td>Patients</td>
                                <td><?php echo $stats['totalPatients']; ?></td>
                            </tr>
                            <tr>
                                <td>Appointments</td>
                                <td><?php echo $stats['totalAppointments']; ?></td>
                            </tr>
                            <tr>
                                <td>Staff</td>
                                <td><?php echo $stats['totalStaff']; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="box">
                <h3 class="mb-4">Quick Actions</h3>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="/clinicus/admin/users" class="btn btn-primary w-100">
                            <i class="fas fa-users"></i> Manage Users
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="/clinicus/admin/doctors" class="btn btn-info w-100">
                            <i class="fas fa-user-md"></i> Manage Doctors
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="/clinicus/admin/appointments" class="btn btn-success w-100">
                            <i class="fas fa-calendar-check"></i> Manage Appointments
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>