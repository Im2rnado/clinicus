<?php
$hideNav = true;
$hideFooter = true;
$title = "Register - Clinicus";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Clinicus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .register-container {
            max-width: 500px;
            margin: 50px auto;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #fff;
            border-bottom: none;
            text-align: center;
            padding: 20px;
        }

        .card-header h3 {
            color: #333;
            margin: 0;
        }

        .form-control {
            border-radius: 5px;
            padding: 10px 15px;
        }

        .btn-primary {
            padding: 10px;
            border-radius: 5px;
        }

        .alert {
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="box">
                    <h2 class="text-center mb-4">Create an Account</h2>

                    <?php if (isset($errors['register'])): ?>
                        <div class="alert alert-danger"><?php echo $errors['register']; ?></div>
                    <?php endif; ?>

                    <form method="POST" action="./register" class="needs-validation" novalidate>
                        <div class="row">
                            <!-- Personal Information -->
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text"
                                    class="form-control <?php echo isset($errors['first_name']) ? 'is-invalid' : ''; ?>"
                                    id="first_name" name="first_name" value="<?php echo $first_name ?? ''; ?>" required>
                                <?php if (isset($errors['first_name'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['first_name']; ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text"
                                    class="form-control <?php echo isset($errors['last_name']) ? 'is-invalid' : ''; ?>"
                                    id="last_name" name="last_name" value="<?php echo $last_name ?? ''; ?>" required>
                                <?php if (isset($errors['last_name'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['last_name']; ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Contact Information -->
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email"
                                    class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>"
                                    id="email" name="email" value="<?php echo $email ?? ''; ?>" required>
                                <?php if (isset($errors['email'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['email']; ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel"
                                    class="form-control <?php echo isset($errors['phone']) ? 'is-invalid' : ''; ?>"
                                    id="phone" name="phone" value="<?php echo $phone ?? ''; ?>" required>
                                <?php if (isset($errors['phone'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['phone']; ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Address Information -->
                            <div class="col-12 mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea
                                    class="form-control <?php echo isset($errors['address']) ? 'is-invalid' : ''; ?>"
                                    id="address" name="address" rows="2"
                                    required><?php echo $address ?? ''; ?></textarea>
                                <?php if (isset($errors['address'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['address']; ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Additional Information -->
                            <div class="col-md-6 mb-3">
                                <label for="dob" class="form-label">Date of Birth</label>
                                <input type="date"
                                    class="form-control <?php echo isset($errors['dob']) ? 'is-invalid' : ''; ?>"
                                    id="dob" name="dob" value="<?php echo $dob ?? ''; ?>" required>
                                <?php if (isset($errors['dob'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['dob']; ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-select <?php echo isset($errors['role']) ? 'is-invalid' : ''; ?>"
                                    id="role" name="role" required onchange="toggleDoctorFields()">
                                    <option value="">Select Role</option>
                                    <option value="1" <?php echo (isset($role) && $role == '1') ? 'selected' : ''; ?>>
                                        Admin</option>
                                    <option value="2" <?php echo (isset($role) && $role == '2') ? 'selected' : ''; ?>>
                                        Doctor</option>
                                    <option value="3" <?php echo (isset($role) && $role == '3') ? 'selected' : ''; ?>>
                                        Patient</option>
                                </select>
                                <?php if (isset($errors['role'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['role']; ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Doctor-specific fields -->
                            <div id="doctorFields" style="display: none;">
                                <div class="col-md-6 mb-3">
                                    <label for="doctorType" class="form-label">Specialization</label>
                                    <select class="form-select <?php echo isset($errors['doctorType']) ? 'is-invalid' : ''; ?>"
                                        id="doctorType" name="doctorType">
                                        <option value="">Select Specialization</option>
                                        <?php foreach ($specializations ?? [] as $spec): ?>
                                            <option value="<?php echo $spec['ID']; ?>" 
                                                <?php echo (isset($doctorType) && $doctorType == $spec['ID']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($spec['Specialization']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if (isset($errors['doctorType'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['doctorType']; ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="yearsOfExperience" class="form-label">Years of Experience</label>
                                    <input type="number" min="0" max="50"
                                        class="form-control <?php echo isset($errors['yearsOfExperience']) ? 'is-invalid' : ''; ?>"
                                        id="yearsOfExperience" name="yearsOfExperience" 
                                        value="<?php echo $yearsOfExperience ?? ''; ?>">
                                    <?php if (isset($errors['yearsOfExperience'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['yearsOfExperience']; ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="consultation_fee" class="form-label">Consultation Fee</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" min="0" step="0.01"
                                            class="form-control <?php echo isset($errors['consultation_fee']) ? 'is-invalid' : ''; ?>"
                                            id="consultation_fee" name="consultation_fee" 
                                            value="<?php echo $consultation_fee ?? ''; ?>">
                                    </div>
                                    <?php if (isset($errors['consultation_fee'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['consultation_fee']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Account Information -->
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text"
                                    class="form-control <?php echo isset($errors['username']) ? 'is-invalid' : ''; ?>"
                                    id="username" name="username" value="<?php echo $username ?? ''; ?>" required>
                                <?php if (isset($errors['username'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['username']; ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password"
                                    class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>"
                                    id="password" name="password" required>
                                <?php if (isset($errors['password'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['password']; ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="col-12 mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <input type="password"
                                    class="form-control <?php echo isset($errors['confirm_password']) ? 'is-invalid' : ''; ?>"
                                    id="confirm_password" name="confirm_password" required>
                                <?php if (isset($errors['confirm_password'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['confirm_password']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">Register</button>
                        </div>

                        <div class="text-center mt-3">
                            <p class="mb-0">Already have an account? <a href="./login">Login here</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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

        // Toggle doctor fields based on role selection
        function toggleDoctorFields() {
            const roleSelect = document.getElementById('role');
            const doctorFields = document.getElementById('doctorFields');
            const doctorType = document.getElementById('doctorType');
            const yearsOfExperience = document.getElementById('yearsOfExperience');
            const consultation_fee = document.getElementById('consultation_fee');

            if (roleSelect.value === '2') {
                doctorFields.style.display = 'block';
                doctorType.required = true;
                yearsOfExperience.required = true;
                consultation_fee.required = true;
            } else {
                doctorFields.style.display = 'none';
                doctorType.required = false;
                yearsOfExperience.required = false;
                consultation_fee.required = false;
            }
        }

        // Call on page load to set initial state
        document.addEventListener('DOMContentLoaded', toggleDoctorFields);
    </script>
</body>

</html>