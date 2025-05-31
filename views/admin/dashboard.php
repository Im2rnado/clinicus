<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$title = "Admin Dashboard - Clinicus";
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
                                <td><?php echo $stats['totalUsers'] ?? '0'; ?></td>
                            </tr>
                            <tr>
                                <td>Doctors</td>
                                <td><?php echo $stats['totalDoctors'] ?? '0'; ?></td>
                            </tr>
                            <tr>
                                <td>Patients</td>
                                <td><?php echo $stats['totalPatients'] ?? '0'; ?></td>
                            </tr>
                            <tr>
                                <td>Appointments</td>
                                <td><?php echo $stats['totalAppointments'] ?? '0'; ?></td>
                            </tr>
                            <tr>
                                <td>Staff</td>
                                <td><?php echo $stats['totalStaff'] ?? '0'; ?></td>
                            </tr>
                            <tr>
                                <td>Medical Records</td>
                                <td><?php echo $stats['totalMedicalHistory'] ?? '0'; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="col-md-6 mb-4">
            <div class="box">
                <h3 class="mb-4">Recent Activity</h3>
                <div class="list-group">
                    <?php if (!empty($recentAppointments)): ?>
                        <?php foreach ($recentAppointments as $appointment): ?>
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">
                                        <?php echo htmlspecialchars($appointment['patientName']); ?> with Dr.
                                        <?php echo htmlspecialchars($appointment['doctorName']); ?>
                                    </h6>
                                    <small class="text-<?php echo $appointment['statusColor']; ?>">
                                        <?php echo $appointment['status']; ?>
                                    </small>
                                </div>
                                <p class="mb-1"><?php echo date('F j, Y g:i A', strtotime($appointment['date'])); ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="list-group-item">
                            <p class="mb-0 text-muted">No recent activity</p>
                        </div>
                    <?php endif; ?>
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
                    <div class="col-md-4 mb-3">
                        <a href="/clinicus/admin/users" class="btn btn-primary w-100">
                            <i class="fas fa-users"></i> Manage Users
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="/clinicus/admin/doctors" class="btn btn-info w-100">
                            <i class="fas fa-user-md"></i> Manage Doctors
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="/clinicus/admin/appointments" class="btn btn-success w-100">
                            <i class="fas fa-calendar-check"></i> Manage Appointments
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>