<?php
$title = 'Book Appointment';
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Book New Appointment</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($errors['system'])): ?>
                            <div class="alert alert-danger">
                                <?php echo $errors['system']; ?>
                            </div>
                    <?php endif; ?>

                    <form method="POST" action="./create" id="appointmentForm">
                        <div class="mb-3">
                            <label for="doctor_id" class="form-label" data-translate="select_doctor">Select Doctor</label>
                            <select class="form-select <?php echo isset($errors['doctor_id']) ? 'is-invalid' : ''; ?>" 
                                    id="doctor_id" name="doctor_id" required>
                                <option value="">Choose a doctor...</option>
                                <?php foreach ($doctors as $doctor): ?>
                                        <option value="<?php echo $doctor['ID']; ?>" 
                                                <?php echo (isset($doctor_id) && $doctor_id == $doctor['ID']) ? 'selected' : ''; ?>>
                                            Dr. <?php echo htmlspecialchars($doctor['FirstName'] . ' ' . $doctor['LastName']); ?>
                                        </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['doctor_id'])): ?>
                                    <div class="invalid-feedback">
                                        <?php echo $errors['doctor_id']; ?>
                                    </div>
                            <?php endif; ?>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="date" class="form-label" data-translate="appointment_date">Appointment Date</label>
                                <input type="date" class="form-control <?php echo isset($errors['date']) ? 'is-invalid' : ''; ?>" 
                                       id="date" name="date" value="<?php echo $date ?? ''; ?>" required>
                                <?php if (isset($errors['date'])): ?>
                                        <div class="invalid-feedback">
                                            <?php echo $errors['date']; ?>
                                        </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="time" class="form-label" data-translate="appointment_time">Appointment Time</label>
                                <input type="time" class="form-control <?php echo isset($errors['time']) ? 'is-invalid' : ''; ?>" 
                                       id="time" name="time" value="<?php echo $time ?? ''; ?>" required>
                                <?php if (isset($errors['time'])): ?>
                                        <div class="invalid-feedback">
                                            <?php echo $errors['time']; ?>
                                        </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="reason" class="form-label" data-translate="appointment_reason">Reason for Visit</label>
                            <textarea class="form-control <?php echo isset($errors['reason']) ? 'is-invalid' : ''; ?>" 
                                      id="reason" name="reason" rows="3" required><?php echo $reason ?? ''; ?></textarea>
                            <?php if (isset($errors['reason'])): ?>
                                    <div class="invalid-feedback">
                                        <?php echo $errors['reason']; ?>
                                    </div>
                            <?php endif; ?>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary" data-translate="book_appointment">
                                <i class="fas fa-calendar-check"></i> Book Appointment
                            </button>
                            <a href="./appointments" class="btn btn-secondary" data-translate="cancel">
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
    // Set minimum date to today
    const dateInput = document.getElementById('date');
    const today = new Date().toISOString().split('T')[0];
    dateInput.min = today;

    // Handle language changes
    const languageSwitcher = document.querySelector('.language-switcher select');
    if (languageSwitcher) {
        languageSwitcher.addEventListener('change', function() {
            const language = this.value;
            
            // Get translations for the new language
            fetch('/appointments/getTranslations', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ language: language })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update all elements with data-translate attribute
                    document.querySelectorAll('[data-translate]').forEach(element => {
                        const key = element.getAttribute('data-translate');
                        if (data.translations[key]) {
                            if (element.tagName === 'INPUT' || element.tagName === 'TEXTAREA') {
                                element.placeholder = data.translations[key];
                            } else {
                                element.textContent = data.translations[key];
                            }
                        }
                    });
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }
});
</script> 