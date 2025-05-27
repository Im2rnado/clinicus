<?php
$hideNav = true;
$hideFooter = true;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Clinicus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .login-container {
            max-width: 400px;
            margin: 100px auto;
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
    <div class="container">
        <div class="login-container">
            <div class="card">
                <div class="card-header">
                    <h3>Welcome to Clinicus</h3>
                    <p class="text-muted">Please login to your account</p>
                </div>
                <div class="card-body p-4">
                    <?php if (isset($errors['login'])): ?>
                        <div class="alert alert-danger">
                            <?php echo $errors['login']; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="./login">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text"
                                class="form-control <?php echo isset($errors['username']) ? 'is-invalid' : ''; ?>"
                                id="username" name="username"
                                value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>" required>
                            <?php if (isset($errors['username'])): ?>
                                <div class="invalid-feedback">
                                    <?php echo $errors['username']; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password"
                                class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>"
                                id="password" name="password" required>
                            <?php if (isset($errors['password'])): ?>
                                <div class="invalid-feedback">
                                    <?php echo $errors['password']; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-3">
                        <p>Don't have an account? <a href="./register">Register here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>