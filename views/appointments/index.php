<?php
$title = "My Appointments - Clinicus";
?>

<div class="container py-4">
    <div class="box">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>My Appointments</h2>
            <a href="/clinicus/appointments/create" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i> Book New Appointment
            </a>
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

        <?php if (empty($appointments)): ?>
            <div class="alert alert-info">
                No appointments found.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date & Time</th>
                            <th>Doctor</th>
                            <th>Specialization</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($appointments as $appointment): ?>
                            <tr>
                                <td>
                                    <?php echo date('M d, Y', strtotime($appointment['appointmentDate'])); ?><br>
                                    <small class="text-muted">
                                        <?php echo date('h:i A', strtotime($appointment['appointmentTime'])); ?>
                                    </small>
                                </td>
                                <td>
                                    Dr.
                                    <?php echo htmlspecialchars($appointment['doctorName'] . ' ' . $appointment['doctorLastName']); ?>
                                </td>
                                <td><?php echo htmlspecialchars($appointment['specialization']); ?></td>
                                <td>
                                    <span class="badge bg-<?php
                                    echo match ($appointment['status']) {
                                        'scheduled' => 'primary',
                                        'completed' => 'success',
                                        'cancelled' => 'danger',
                                        default => 'secondary'
                                    };
                                    ?>">
                                        <?php echo ucfirst($appointment['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if (isset($appointment['payment'])): ?>
                                        <span
                                            class="badge bg-<?php echo $appointment['payment']['status'] === 'completed' ? 'success' : 'warning'; ?>">
                                            <?php echo ucfirst($appointment['payment']['status']); ?>
                                        </span>
                                        <br>
                                        <small class="text-muted">
                                            $<?php echo number_format($appointment['payment']['amount'], 2); ?>
                                        </small>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Unpaid</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="/clinicus/appointments/view/<?php echo $appointment['ID']; ?>"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if ($appointment['status'] === 'scheduled'): ?>
                                            <a href="/clinicus/appointments/cancel/<?php echo $appointment['ID']; ?>"
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Are you sure you want to cancel this appointment?')">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (isset($appointment['payment'])): ?>
                                            <a href="/clinicus/payments/show/<?php echo $appointment['payment']['ID']; ?>"
                                                class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-receipt"></i>
                                            </a>
                                        <?php endif; ?>
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