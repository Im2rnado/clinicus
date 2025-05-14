<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinicus Admin - <?php echo $title ?? 'Dashboard'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        .sidebar {
            min-height: calc(100vh - 56px);
            background: #f8f9fa;
            padding: 20px;
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
        }

        .sidebar .list-group-item {
            border-radius: 0;
            border-left: 0;
            border-right: 0;
        }

        .sidebar .list-group-item.active {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .content-wrapper {
            padding: 20px;
        }

        .navbar-brand {
            font-weight: bold;
        }

        .alert-dismissible {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="/clinicus/admin">Clinicus Admin</a>
            <div class="d-flex">
                <a href="/clinicus/" class="btn btn-outline-light me-2">
                    <i class="bi bi-house"></i> Back to Home
                </a>
                <a href="/clinicus/logout.php" class="btn btn-outline-light">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 sidebar">
                <h5 class="mb-3">Database Tables</h5>
                <div class="list-group mb-4">
                    <a href="/clinicus/admin"
                        class="list-group-item list-group-item-action <?php echo !isset($tableName) ? 'active' : ''; ?>">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <a href="/clinicus/admin/users"
                        class="list-group-item list-group-item-action <?php echo isset($tableName) && $tableName == 'users' ? 'active' : ''; ?>">
                        <i class="bi bi-people"></i> Users
                    </a>
                    <a href="/clinicus/admin/usertype"
                        class="list-group-item list-group-item-action <?php echo isset($tableName) && $tableName == 'usertype' ? 'active' : ''; ?>">
                        <i class="bi bi-person-badge"></i> User Types
                    </a>
                    <a href="/clinicus/admin/doctors"
                        class="list-group-item list-group-item-action <?php echo isset($tableName) && $tableName == 'doctors' ? 'active' : ''; ?>">
                        <i class="bi bi-clipboard2-pulse"></i> Doctors
                    </a>
                    <a href="/clinicus/admin/patients"
                        class="list-group-item list-group-item-action <?php echo isset($tableName) && $tableName == 'patients' ? 'active' : ''; ?>">
                        <i class="bi bi-person-plus"></i> Patients
                    </a>
                    <a href="/clinicus/admin/appointments"
                        class="list-group-item list-group-item-action <?php echo isset($tableName) && $tableName == 'appointments' ? 'active' : ''; ?>">
                        <i class="bi bi-calendar-check"></i> Appointments
                    </a>
                    <a href="/clinicus/admin/medical_history"
                        class="list-group-item list-group-item-action <?php echo isset($tableName) && $tableName == 'medical_history' ? 'active' : ''; ?>">
                        <i class="bi bi-journal-medical"></i> Medical History
                    </a>
                    <a href="/clinicus/admin/medications"
                        class="list-group-item list-group-item-action <?php echo isset($tableName) && $tableName == 'medications' ? 'active' : ''; ?>">
                        <i class="bi bi-file-earmark-medical"></i> Medications
                    </a>
                    <a href="/clinicus/admin/prescriptions"
                        class="list-group-item list-group-item-action <?php echo isset($tableName) && $tableName == 'prescriptions' ? 'active' : ''; ?>">
                        <i class="bi bi-file-earmark-medical"></i> Prescriptions
                    </a>
                    <a href="/clinicus/admin/staff"
                        class="list-group-item list-group-item-action <?php echo isset($tableName) && $tableName == 'staff' ? 'active' : ''; ?>">
                        <i class="bi bi-person-workspace"></i> Staff
                    </a>
                    <a href="/clinicus/admin/audit_logs"
                        class="list-group-item list-group-item-action <?php echo isset($tableName) && $tableName == 'audit_logs' ? 'active' : ''; ?>">
                        <i class="bi bi-list-check"></i> Audit Logs
                    </a>
                </div>
            </div>
            <!-- Notification system kind of -->
            <div class="col-md-10 content-wrapper">
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php
                        $message = 'Operation completed successfully';
                        if ($_GET['success'] == 'created')
                            $message = 'Record created successfully';
                        if ($_GET['success'] == 'updated')
                            $message = 'Record updated successfully';
                        if ($_GET['success'] == 'deleted')
                            $message = 'Record deleted successfully';
                        echo $message;
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Error: <?php echo htmlspecialchars($_GET['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php echo $content ?? ''; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>