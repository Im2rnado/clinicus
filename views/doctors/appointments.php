<?php
$title = "Appointments - Clinicus";
?>

<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>Appointments</h2>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php
            echo $_SESSION['success'];
            unset($_SESSION['success']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php
            echo $_SESSION['error'];
            unset($_SESSION['error']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <?php if (empty($appointments)): ?>
                <p class="text-muted">No appointments found.</p>
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
                            <?php foreach ($appointments as $appointment): ?>
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
                                        <div class="btn-group">
                                            <a href="/clinicus/doctor/viewAppointment/<?php echo $appointment['ID']; ?>"
                                                class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <?php if ($appointment['status'] == 0): ?>
                                                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                                                    data-bs-target="#completeModal<?php echo $appointment['ID']; ?>">
                                                    <i class="fas fa-check"></i> Complete
                                                </button>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Complete Appointment Modal -->
                                        <div class="modal fade" id="completeModal<?php echo $appointment['ID']; ?>"
                                            tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Complete Appointment</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <form action="/clinicus/doctor/updateAppointmentStatus" method="POST">
                                                        <div class="modal-body">
                                                            <p>Are you sure you want to mark this appointment as completed?</p>
                                                            <input type="hidden" name="appointment_id"
                                                                value="<?php echo $appointment['ID']; ?>">
                                                            <input type="hidden" name="status" value="1">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-success">Complete
                                                                Appointment</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
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