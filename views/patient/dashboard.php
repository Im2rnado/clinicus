<?php
$title = "Patient Dashboard - Clinicus";
?>

<div class="container py-4">
    <div class="row">
        <!-- Upcoming Appointments -->
        <div class="col-md-6 mb-4">
            <div class="box">
                <h3 class="mb-4">Upcoming Appointments</h3>
                <?php if (empty($appointments)): ?>
                    <p class="text-muted">No upcoming appointments</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Doctor</th>
                                    <th>Specialization</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($appointments as $appointment): ?>
                                    <tr>
                                        <td><?php echo date('M d, Y', strtotime($appointment['appointmentDate'])); ?></td>
                                        <td><?php echo htmlspecialchars($appointment['doctorName']); ?></td>
                                        <td><?php echo htmlspecialchars($appointment['specialization']); ?></td>
                                        <td>
                                            <span class="badge bg-<?php
                                            echo $appointment['status'] == 0 ? 'warning' :
                                                ($appointment['status'] == 1 ? 'success' : 'danger');
                                            ?>">
                                                <?php
                                                echo $appointment['status'] == 0 ? 'Pending' :
                                                    ($appointment['status'] == 1 ? 'Confirmed' : 'Cancelled');
                                                ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
                <div class="text-end mt-3">
                    <a href="/clinicus/appointments" class="btn btn-primary">View All Appointments</a>
                </div>
            </div>
        </div>

        <!-- Recent Medical History -->
        <div class="col-md-6 mb-4">
            <div class="box">
                <h3 class="mb-4">Recent Medical History</h3>
                <?php if (empty($medicalHistory)): ?>
                    <p class="text-muted">No medical history available</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Doctor</th>
                                    <th>Diagnosis</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($medicalHistory as $record): ?>
                                    <tr>
                                        <td><?php echo date('M d, Y', strtotime($record['date'])); ?></td>
                                        <td><?php echo htmlspecialchars($record['doctorName']); ?></td>
                                        <td><?php echo htmlspecialchars($record['diagnosis']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
                <div class="text-end mt-3">
                    <a href="/clinicus/patient/medicalHistory" class="btn btn-primary">View Full History</a>
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
                        <a href="/clinicus/appointments/create" class="btn btn-primary w-100">
                            <i class="fas fa-calendar-plus"></i> Book Appointment
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="/clinicus/patient/profile" class="btn btn-secondary w-100">
                            <i class="fas fa-user-edit"></i> Update Profile
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="/clinicus/patient/messages" class="btn btn-success w-100">
                            <i class="fas fa-envelope"></i> Messages
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>