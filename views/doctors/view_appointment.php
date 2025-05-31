<?php
$title = "View Appointment - Clinicus";
?>

<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Appointment Details</h2>
                <a href="/clinicus/doctor/appointments" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Appointments
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Appointment Information -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Appointment Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Date & Time</label>
                        <p><?php echo date('F d, Y h:i A', strtotime($appointment['appointmentDate'])); ?></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Status</label>
                        <p>
                            <span class="badge bg-<?php echo $appointment['status'] == 1 ? 'success' : 'warning'; ?>">
                                <?php echo $appointment['status'] == 1 ? 'Completed' : 'Pending'; ?>
                            </span>
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Payment Status</label>
                        <p>
                            <?php
                            $paymentStatus = isset($appointment['paymentStatus']) ? $appointment['paymentStatus'] : 'unpaid';
                            $statusClass = $paymentStatus === 'paid' ? 'success' : 'danger';
                            ?>
                            <span class="badge bg-<?php echo $statusClass; ?>">
                                <?php echo ucfirst($paymentStatus); ?>
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Patient Information -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Patient Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Name</label>
                        <p><?php echo htmlspecialchars($patient['FirstName'] . ' ' . $patient['LastName']); ?></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <p><?php echo htmlspecialchars($patient['email']); ?></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Phone</label>
                        <p><?php echo htmlspecialchars($patient['phone']); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Medical History -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Medical History</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($medicalHistory)): ?>
                        <p class="text-muted">No medical history available for this patient.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Service Type</th>
                                        <th>Description</th>
                                        <th>Doctor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($medicalHistory as $record): ?>
                                        <tr>
                                            <td><?php echo date('M d, Y', strtotime($record['date'])); ?></td>
                                            <td><?php echo htmlspecialchars($record['serviceType'] ?? 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($record['description']); ?></td>
                                            <td><?php echo htmlspecialchars($record['doctorName']); ?></td>
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