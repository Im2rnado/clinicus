<?php
$title = 'My Appointments';
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">My Appointments</h1>
        <a href="/clinicus/appointments/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Book New Appointment
        </a>
    </div>

    <?php if (empty($appointments)): ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> You don't have any appointments yet.
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($appointments as $appointment): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="card-title mb-0">
                                    Dr. <?php echo htmlspecialchars($appointment['doctorName']); ?>
                                </h5>
                                <span class="badge bg-<?php echo $appointment['statusColor']; ?>">
                                    <?php echo $appointment['status']; ?>
                                </span>
                            </div>

                            <div class="mb-3">
                                <p class="card-text">
                                    <i class="fas fa-calendar-alt text-primary me-2"></i>
                                    <?php echo date('F j, Y', strtotime($appointment['appointmentDate'])); ?>
                                </p>
                                <p class="card-text">
                                    <i class="fas fa-clock text-primary me-2"></i>
                                    <?php echo date('g:i A', strtotime($appointment['appointmentDate'])); ?>
                                </p>
                                <p class="card-text">
                                    <i class="fas fa-comment-alt text-primary me-2"></i>
                                    <?php echo htmlspecialchars($appointment['reason']); ?>
                                </p>
                            </div>

                            <div class="d-flex gap-2">
                                <?php if ($appointment['status'] === 'Pending'): ?>
                                    <form method="POST" action="./cancel/<?php echo $appointment['ID']; ?>"
                                        class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to cancel this appointment?');">
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-times"></i> Cancel
                                        </button>
                                    </form>
                                    <a href="/clinicus/payments/create/<?php echo $appointment['ID']; ?>" class="btn btn-sm btn-success">
                                        <i class="fas fa-credit-card"></i> Pay
                                    </a>
                                <?php elseif ($appointment['status'] === 'Completed'): ?>
                                    <a href="/clinicus/ratings/create/<?php echo $appointment['ID']; ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-star"></i> Rate
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>