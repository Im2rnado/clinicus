<?php
$title = "Profile - Clinicus";
?>

<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>Profile Management</h2>
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

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Personal Information</h5>
                </div>
                <div class="card-body">
                    <form action="/clinicus/doctor/updateProfile" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name"
                                    value="<?php echo htmlspecialchars($doctor['FirstName'] ?? ''); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name"
                                    value="<?php echo htmlspecialchars($doctor['LastName'] ?? ''); ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="<?php echo htmlspecialchars($doctor['email'] ?? ''); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="phone" name="phone"
                                    value="<?php echo htmlspecialchars($doctor['phone'] ?? ''); ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="consultation_fee" class="form-label">Consultation Fee ($)</label>
                            <input type="number"
                                class="form-control <?php echo isset($_SESSION['form_errors']['consultation_fee']) ? 'is-invalid' : ''; ?>"
                                id="consultation_fee" name="consultation_fee"
                                value="<?php echo htmlspecialchars($doctor['consultation_fee'] ?? '0'); ?>" required
                                min="0" step="0.01">
                            <?php if (isset($_SESSION['form_errors']['consultation_fee'])): ?>
                                <div class="invalid-feedback">
                                    <?php
                                    echo $_SESSION['form_errors']['consultation_fee'];
                                    unset($_SESSION['form_errors']['consultation_fee']);
                                    ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Account Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Total Patients</label>
                        <p><?php echo $doctor['totalPatients'] ?? 0; ?></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Total Appointments</label>
                        <p><?php echo $doctor['totalAppointments'] ?? 0; ?></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Rating</label>
                        <p>
                            <i class="fas fa-star text-warning"></i>
                            <?php echo number_format($doctor['rating'] ?? 0, 1); ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Specialization</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">
                        <i class="fas fa-graduation-cap me-2"></i>
                        <?php echo htmlspecialchars($doctor['specialization'] ?? 'Not specified'); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>