<?php
$title = "Payment Details - Clinicus";
?>

<div class="container py-4">
    <div class="box">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Payment Details</h2>
            <a href="/clinicus/payments" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Back to Payments
            </a>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Payment Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Transaction ID:</strong><br>
                                    <span class="text-muted"><?php echo $payment['transactionID']; ?></span>
                                </p>
                                <p><strong>Payment Date:</strong><br>
                                    <span class="text-muted"><?php echo date('F j, Y g:i A', strtotime($payment['createdAt'])); ?></span>
                                </p>
                                <p><strong>Payment Method:</strong><br>
                                    <span class="badge bg-info">
                                        <?php echo ucfirst(str_replace('_', ' ', $payment['paymentMethod'])); ?>
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Amount:</strong><br>
                                    <span class="h4 text-success">$<?php echo number_format($payment['amount'], 2); ?></span>
                                </p>
                                <p><strong>Status:</strong><br>
                                    <span class="badge bg-<?php echo $payment['status'] === 'completed' ? 'success' : 'warning'; ?>">
                                        <?php echo ucfirst($payment['status']); ?>
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Appointment Details</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Doctor:</strong><br>
                                    <span class="text-muted">Dr. <?php echo htmlspecialchars($payment['doctorFirstName'] . ' ' . $payment['doctorLastName']); ?></span>
                                </p>
                                <p><strong>Specialization:</strong><br>
                                    <span class="text-muted"><?php echo htmlspecialchars($payment['specialization']); ?></span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Appointment Date:</strong><br>
                                    <span class="text-muted"><?php echo date('F j, Y', strtotime($payment['appointmentDate'])); ?></span>
                                </p>
                                <p><strong>Appointment Time:</strong><br>
                                    <span class="text-muted"><?php echo date('h:i A', strtotime($payment['appointmentTime'])); ?></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Receipt</h5>
                        <div class="text-center mb-4">
                            <i class="fas fa-receipt fa-3x text-primary mb-3"></i>
                            <h6>Payment Receipt</h6>
                            <p class="text-muted small"><?php echo $payment['transactionID']; ?></p>
                        </div>
                        <hr>
                        <div class="d-grid">
                            <button class="btn btn-outline-primary" onclick="window.print()">
                                <i class="fas fa-print me-2"></i> Print Receipt
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .btn, .no-print {
            display: none !important;
        }
        .box {
            border: none !important;
            box-shadow: none !important;
        }
    }
</style>