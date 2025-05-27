<?php
$title = 'Payment Details';
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Payment Details</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Appointment Details</h5>
                            <p class="mb-1">
                                <strong>Doctor:</strong>
                                Dr. <?php echo htmlspecialchars($payment['doctorName']); ?>
                            </p>
                            <p class="mb-1">
                                <strong>Date:</strong>
                                <?php echo date('F j, Y', strtotime($payment['appointmentDate'])); ?>
                            </p>
                            <p class="mb-0">
                                <strong>Time:</strong>
                                <?php echo date('g:i A', strtotime($payment['appointmentDate'])); ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h5>Payment Information</h5>
                            <p class="mb-1">
                                <strong>Transaction ID:</strong>
                                <?php echo htmlspecialchars($payment['transactionID']); ?>
                            </p>
                            <p class="mb-1">
                                <strong>Payment Method:</strong>
                                <?php echo htmlspecialchars($payment['paymentMethod']); ?>
                            </p>
                            <p class="mb-0">
                                <strong>Amount:</strong>
                                $<?php echo number_format($payment['amount'], 2); ?>
                            </p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5>Payment Status</h5>
                        <?php
                        $statusClass = [
                            'pending' => 'bg-warning',
                            'completed' => 'bg-success',
                            'failed' => 'bg-danger',
                            'refunded' => 'bg-info'
                        ];
                        $statusClass = $statusClass[$payment['status']] ?? 'bg-secondary';
                        ?>
                        <span class="badge <?php echo $statusClass; ?> fs-5">
                            <?php echo ucfirst($payment['status']); ?>
                        </span>
                    </div>

                    <div class="mb-4">
                        <h5>Timeline</h5>
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-marker bg-primary"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-0">Payment Created</h6>
                                    <small class="text-muted">
                                        <?php echo date('F j, Y g:i A', strtotime($payment['createdAt'])); ?>
                                    </small>
                                </div>
                            </div>
                            <?php if ($payment['updatedAt'] !== $payment['createdAt']): ?>
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-info"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-0">Payment Updated</h6>
                                        <small class="text-muted">
                                            <?php echo date('F j, Y g:i A', strtotime($payment['updatedAt'])); ?>
                                        </small>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="./payments" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Payments
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .timeline {
        position: relative;
        padding: 20px 0;
    }

    .timeline-item {
        position: relative;
        padding-left: 40px;
        margin-bottom: 20px;
    }

    .timeline-marker {
        position: absolute;
        left: 0;
        top: 0;
        width: 15px;
        height: 15px;
        border-radius: 50%;
    }

    .timeline-item:not(:last-child)::before {
        content: '';
        position: absolute;
        left: 7px;
        top: 15px;
        height: calc(100% + 5px);
        width: 2px;
        background-color: #dee2e6;
    }

    .timeline-content {
        padding: 10px;
        background-color: #f8f9fa;
        border-radius: 4px;
    }
</style>