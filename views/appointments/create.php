<?php
$title = "Book Appointment - Clinicus";
?>

<div class="container py-4">
    <div class="box">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Book Appointment</h2>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php
                echo $_SESSION['error'];
                unset($_SESSION['error']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if ($step === 1): ?>
            <!-- Step 1: Select Specialization -->
            <form method="POST" action="/clinicus/appointments/create">
                <div class="mb-4">
                    <h4>Step 1: Select Specialization</h4>
                    <p class="text-muted">Choose the type of doctor you need to see</p>
                </div>

                <div class="mb-3">
                    <label for="specialization" class="form-label">Specialization</label>
                    <select class="form-select" id="specialization" name="specialization" required>
                        <option value="">Select a specialization</option>
                        <?php foreach ($specializations as $spec): ?>
                            <option value="<?php echo $spec['ID']; ?>">
                                <?php echo htmlspecialchars($spec['Specialization']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        Next <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                </div>
            </form>

        <?php elseif ($step === 2): ?>
            <!-- Step 2: Select Doctor and Book Appointment -->
            <form method="POST" action="/clinicus/appointments/create">
                <input type="hidden" name="specialization" value="<?php echo htmlspecialchars($specialization); ?>">
                
                <div class="mb-4">
                    <h4>Step 2: Select Doctor and Book Appointment</h4>
                    <p class="text-muted">Choose a doctor and select your preferred appointment time</p>
                </div>

                <?php if (empty($doctors)): ?>
                    <div class="alert alert-warning">
                        No doctors available for this specialization at the moment.
                    </div>
                <?php else: ?>
                    <div class="row mb-4">
                        <?php foreach ($doctors as $doctor): ?>
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="doctor_id" 
                                                   id="doctor_<?php echo $doctor['ID']; ?>" 
                                                   value="<?php echo $doctor['ID']; ?>" required>
                                            <label class="form-check-label" for="doctor_<?php echo $doctor['ID']; ?>">
                                                <h5 class="card-title mb-1">
                                                    Dr. <?php echo htmlspecialchars($doctor['FirstName'] . ' ' . $doctor['LastName']); ?>
                                                </h5>
                                                <p class="card-text text-muted mb-2">
                                                    <i class="fas fa-graduation-cap me-2"></i>
                                                    <?php echo htmlspecialchars($doctor['specialization']); ?>
                                                </p>
                                                <p class="card-text mb-2">
                                                    <i class="fas fa-briefcase me-2"></i>
                                                    <?php echo $doctor['yearsOfExperince']; ?> years of experience
                                                </p>
                                                <p class="card-text">
                                                    <i class="fas fa-star me-2 text-warning"></i>
                                                    <?php echo number_format($doctor['rating'], 1); ?> rating
                                                </p>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="appointment_date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="appointment_date" name="appointment_date" 
                                   min="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="appointment_time" class="form-label">Time</label>
                            <select class="form-select" id="appointment_time" name="appointment_time" required>
                                <option value="">Select a time</option>
                                <?php
                                $start = strtotime('09:00');
                                $end = strtotime('17:00');
                                $interval = 30 * 60; // 30 minutes
                                
                                for ($time = $start; $time <= $end; $time += $interval) {
                                    $timeValue = date('H:i', $time);
                                    $timeDisplay = date('h:i A', $time);
                                    echo "<option value=\"$timeValue\">$timeDisplay</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason for Visit</label>
                        <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="/clinicus/appointments/create" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Proceed to Payment <i class="fas fa-credit-card ms-2"></i>
                        </button>
                    </div>
                <?php endif; ?>
            </form>

        <?php else: ?>
            <!-- Step 3: Payment -->
            <form method="POST" action="/clinicus/appointments/create" id="payment-form">
                <input type="hidden" name="doctor_id" value="<?php echo htmlspecialchars($doctor_id); ?>">
                <input type="hidden" name="appointment_date" value="<?php echo htmlspecialchars($appointment_date); ?>">
                <input type="hidden" name="appointment_time" value="<?php echo htmlspecialchars($appointment_time); ?>">
                <input type="hidden" name="reason" value="<?php echo htmlspecialchars($reason); ?>">
                <input type="hidden" name="specialization" value="<?php echo htmlspecialchars($specialization); ?>">
                
                <div class="mb-4">
                    <h4>Step 3: Payment</h4>
                    <p class="text-muted">Complete your payment to confirm the appointment</p>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Appointment Summary</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Doctor:</strong> Dr. <?php echo htmlspecialchars($doctor['FirstName'] . ' ' . $doctor['LastName']); ?></p>
                                <p><strong>Specialization:</strong> <?php echo htmlspecialchars($doctor['specialization']); ?></p>
                                <p><strong>Date:</strong> <?php echo date('F j, Y', strtotime($appointment_date)); ?></p>
                                <p><strong>Time:</strong> <?php echo date('h:i A', strtotime($appointment_time)); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Consultation Fee:</strong> $<?php echo number_format($doctor['consultation_fee'], 2); ?></p>
                                <p><strong>Total Amount:</strong> $<?php echo number_format($doctor['consultation_fee'], 2); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h5>Payment Method</h5>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="payment_method" id="credit_card" value="credit_card" checked required>
                        <label class="form-check-label" for="credit_card">
                            <i class="fas fa-credit-card me-2"></i> Credit Card
                        </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="payment_method" id="debit_card" value="debit_card" required>
                        <label class="form-check-label" for="debit_card">
                            <i class="fas fa-credit-card me-2"></i> Debit Card
                        </label>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="card_number" class="form-label">Card Number</label>
                    <input type="text" class="form-control" id="card_number" name="card_number" required 
                           pattern="[0-9]{16}" maxlength="16" placeholder="1234 5678 9012 3456">
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="expiry_date" class="form-label">Expiry Date</label>
                        <input type="text" class="form-control" id="expiry_date" name="expiry_date" required 
                               pattern="(0[1-9]|1[0-2])\/([0-9]{2})" maxlength="5" placeholder="MM/YY">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="cvv" class="form-label">CVV</label>
                        <input type="text" class="form-control" id="cvv" name="cvv" required 
                               pattern="[0-9]{3,4}" maxlength="4" placeholder="123">
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="javascript:history.back()" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back
                    </a>
                    <button type="submit" class="btn btn-success">
                        Pay & Book Appointment <i class="fas fa-check ms-2"></i>
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>

<script>
    // Set minimum date to today
    document.getElementById('appointment_date').min = new Date().toISOString().split('T')[0];

    // Format card number input
    document.getElementById('card_number').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        e.target.value = value;
    });

    // Format expiry date input
    document.getElementById('expiry_date').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.slice(0,2) + '/' + value.slice(2);
        }
        e.target.value = value;
    });

    // Format CVV input
    document.getElementById('cvv').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        e.target.value = value;
    });
</script>