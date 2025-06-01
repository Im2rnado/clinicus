<?php
$title = "My Appointments - Clinicus";
?>

<div class="container py-4">
    <div class="box">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>My Appointments</h2>
            <a href="/clinicus/appointments/create" class="btn btn-primary">
                <i class="fas fa-calendar-plus"></i> Book New Appointment
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

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php
                echo $_SESSION['error'];
                unset($_SESSION['error']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (empty($appointments)): ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> You have no appointments yet.
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
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($appointments as $appointment): ?>
                            <tr>
                                <td><?php echo date('M d, Y h:i A', strtotime($appointment['appointmentDate'])); ?></td>
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
                                <td>
                                    <?php if ($appointment['totalPrice']): ?>
                                        $<?php echo number_format($appointment['totalPrice'], 2); ?>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($appointment['status'] == 0 || $appointment['status'] == 1): ?>
                                        <a href="/clinicus/patient/cancelAppointment/<?php echo $appointment['ID']; ?>"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure you want to cancel this appointment?');">
                                            <i class="fas fa-times"></i> Cancel
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>