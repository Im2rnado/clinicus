<?php
$title = "My Profile - Clinicus";
?>

<div class="container py-4">
    <div class="box">
        <h2 class="mb-4">My Profile</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php
                echo $_SESSION['success'];
                unset($_SESSION['success']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($errors['update'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $errors['update']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form method="POST" action="/clinicus/patient/profile" class="needs-validation" novalidate>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text"
                        class="form-control <?php echo isset($errors['first_name']) ? 'is-invalid' : ''; ?>"
                        id="first_name" name="first_name"
                        value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>" required>
                    <?php if (isset($errors['first_name'])): ?>
                        <div class="invalid-feedback"><?php echo $errors['first_name']; ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text"
                        class="form-control <?php echo isset($errors['last_name']) ? 'is-invalid' : ''; ?>"
                        id="last_name" name="last_name"
                        value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>" required>
                    <?php if (isset($errors['last_name'])): ?>
                        <div class="invalid-feedback"><?php echo $errors['last_name']; ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>"
                        id="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                    <?php if (isset($errors['email'])): ?>
                        <div class="invalid-feedback"><?php echo $errors['email']; ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="tel" class="form-control <?php echo isset($errors['phone']) ? 'is-invalid' : ''; ?>"
                        id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" required>
                    <?php if (isset($errors['phone'])): ?>
                        <div class="invalid-feedback"><?php echo $errors['phone']; ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea class="form-control <?php echo isset($errors['address']) ? 'is-invalid' : ''; ?>" id="address"
                    name="address" rows="3" required><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                <?php if (isset($errors['address'])): ?>
                    <div class="invalid-feedback"><?php echo $errors['address']; ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="dob" class="form-label">Date of Birth</label>
                <input type="date" class="form-control <?php echo isset($errors['dob']) ? 'is-invalid' : ''; ?>"
                    id="dob" name="dob" value="<?php echo date('Y-m-d', strtotime($user['dob'] ?? '')); ?>" required>
                <?php if (isset($errors['dob'])): ?>
                    <div class="invalid-feedback"><?php echo $errors['dob']; ?></div>
                <?php endif; ?>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Profile
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Form validation
    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })()
</script>