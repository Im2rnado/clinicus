<?php
$title = "Doctor Dashboard - Clinicus";
?>

<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>Welcome, Dr. <?php echo htmlspecialchars($doctor['doctorName']); ?></h2>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <h5 class="card-title">Today's Appointments</h5>
                    <h2 class="card-text"><?php echo count($todayAppointments); ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <h5 class="card-title">Total Patients</h5>
                    <h2 class="card-text"><?php echo $totalPatients; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <h5 class="card-title">Total Appointments</h5>
                    <h2 class="card-text"><?php echo $totalAppointments; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <h5 class="card-title">Rating</h5>
                    <h2 class="card-text"><?php echo number_format($doctor['rating'], 1); ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Appointments -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Today's Appointments</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($todayAppointments)): ?>
                        <p class="text-muted">No appointments scheduled for today.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Time</th>
                                        <th>Patient Name</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($todayAppointments as $appointment): ?>
                                        <tr>
                                            <td><?php echo date('h:i A', strtotime($appointment['appointmentDate'])); ?></td>
                                            <td><?php echo htmlspecialchars($appointment['patientName']); ?></td>
                                            <td>
                                                <span
                                                    class="badge bg-<?php echo $appointment['status'] == 1 ? 'success' : 'warning'; ?>">
                                                    <?php echo $appointment['status'] == 1 ? 'Completed' : 'Pending'; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="/clinicus/doctor/viewAppointment/<?php echo $appointment['ID']; ?>"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Appointments -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Upcoming Appointments</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($upcomingAppointments)): ?>
                        <p class="text-muted">No upcoming appointments scheduled.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Patient Name</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($upcomingAppointments as $appointment): ?>
                                        <tr>
                                            <td><?php echo date('M d, Y', strtotime($appointment['appointmentDate'])); ?></td>
                                            <td><?php echo date('h:i A', strtotime($appointment['appointmentDate'])); ?></td>
                                            <td><?php echo htmlspecialchars($appointment['patientName']); ?></td>
                                            <td>
                                                <span
                                                    class="badge bg-<?php echo $appointment['status'] == 1 ? 'success' : 'warning'; ?>">
                                                    <?php echo $appointment['status'] == 1 ? 'Completed' : 'Pending'; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="/clinicus/doctor/viewAppointment/<?php echo $appointment['ID']; ?>"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>