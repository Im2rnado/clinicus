<?php
$title = 'Payment';
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Payment Details</h4>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h5>Appointment Details</h5>
                        <p class="mb-1">
                            <strong>Doctor:</strong> Dr. <?php echo htmlspecialchars($appointment['doctorName']); ?>
                        </p>
                        <p class="mb-1">
                            <strong>Date:</strong> <?php echo date('F j, Y', strtotime($appointment['appointmentDate'])); ?>
                        </p>
                        <p class="mb-0">
                            <strong>Time:</strong> <?php echo date('g:i A', strtotime($appointment['appointmentDate'])); ?>
                        </p>
                    </div>

                    <div class="mb-4">
                        <h5>Payment Amount</h5>
                        <div class="display-4 text-primary mb-0">$100.00</div>
                        <small class="text-muted">Consultation Fee</small>
                    </div>

                    <form method="POST" action="./create/<?php echo $appointment['ID']; ?>" id="paymentForm">
                        <?php if (isset($errors['system'])): ?>
                            <div class="alert alert-danger">
                                <?php echo $errors['system']; ?>
                            </div>
                        <?php endif; ?>

                        <div class="mb-4">
                            <label class="form-label">Payment Method</label>
                            <div class="row">
                                <?php foreach ($paymentModel->getPaymentMethods() as $value => $label): ?>
                                    <div class="col-md-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_method" 
                                                   id="method_<?php echo $value; ?>" value="<?php echo $value; ?>"
                                                   <?php echo (isset($payment_method) && $payment_method === $value) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="method_<?php echo $value; ?>">
                                                <?php echo $label; ?>
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php if (isset($errors['payment_method'])): ?>
                                <div class="invalid-feedback d-block">
                                    <?php echo $errors['payment_method']; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-4">
                            <label for="card_number" class="form-label">Card Number</label>
                            <input type="text" class="form-control <?php echo isset($errors['card_number']) ? 'is-invalid' : ''; ?>" 
                                   id="card_number" name="card_number" value="<?php echo $card_number ?? ''; ?>"
                                   placeholder="1234 5678 9012 3456" maxlength="19">
                            <?php if (isset($errors['card_number'])): ?>
                                <div class="invalid-feedback">
                                    <?php echo $errors['card_number']; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="expiry_date" class="form-label">Expiry Date</label>
                                <input type="text" class="form-control <?php echo isset($errors['expiry_date']) ? 'is-invalid' : ''; ?>" 
                                       id="expiry_date" name="expiry_date" value="<?php echo $expiry_date ?? ''; ?>"
                                       placeholder="MM/YY" maxlength="5">
                                <?php if (isset($errors['expiry_date'])): ?>
                                    <div class="invalid-feedback">
                                        <?php echo $errors['expiry_date']; ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="cvv" class="form-label">CVV</label>
                                <input type="text" class="form-control <?php echo isset($errors['cvv']) ? 'is-invalid' : ''; ?>" 
                                       id="cvv" name="cvv" value="<?php echo $cvv ?? ''; ?>"
                                       placeholder="123" maxlength="3">
                                <?php if (isset($errors['cvv'])): ?>
                                    <div class="invalid-feedback">
                                        <?php echo $errors['cvv']; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-lock"></i> Process Payment
                            </button>
                            <a href="./appointments" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Format card number with spaces
    const cardNumber = document.getElementById('card_number');
    cardNumber.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        let formattedValue = '';
        for (let i = 0; i < value.length; i++) {
            if (i > 0 && i % 4 === 0) {
                formattedValue += ' ';
            }
            formattedValue += value[i];
        }
        e.target.value = formattedValue;
    });

    // Format expiry date
    const expiryDate = document.getElementById('expiry_date');
    expiryDate.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.substring(0, 2) + '/' + value.substring(2);
        }
        e.target.value = value;
    });
});
</script> 