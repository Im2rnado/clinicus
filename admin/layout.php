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
                    <?php
                    $menuItems = [
                        ['url' => '/clinicus/admin/users?table=users', 'icon' => 'bi-people', 'text' => 'Users'],
                        ['url' => '/clinicus/admin/usertype?table=usertype', 'icon' => 'bi-person-badge', 'text' => 'User Types'],
                        ['url' => '/clinicus/admin/doctors?table=doctors', 'icon' => 'bi-clipboard2-pulse', 'text' => 'Doctors'],
                        ['url' => '/clinicus/admin/patients?table=patients', 'icon' => 'bi-person-plus', 'text' => 'Patients'],
                        ['url' => '/clinicus/admin/staff?table=staff', 'icon' => 'bi-person-workspace', 'text' => 'Staff'],
                        ['url' => '/clinicus/admin/appointments?table=appointments', 'icon' => 'bi-calendar-check', 'text' => 'Appointments'],
                        ['url' => '/clinicus/admin/medications?table=medications', 'icon' => 'bi-file-earmark-medical', 'text' => 'Medications'],
                        ['url' => '/clinicus/admin/medical_history?table=medical_history', 'icon' => 'bi-journal-medical', 'text' => 'Medical History'],
                        ['url' => '/clinicus/admin/audit_logs?table=audit_logs', 'icon' => 'bi-list-check', 'text' => 'Audit Logs'],
                        ['url' => '/clinicus/admin/services?table=services', 'icon' => 'bi-briefcase', 'text' => 'Services'],
                        ['url' => '/clinicus/admin/service_types?table=service_types', 'icon' => 'bi-tags', 'text' => 'Service Types'],
                        ['url' => '/clinicus/admin/units?table=units', 'icon' => 'bi-rulers', 'text' => 'Units'],
                        ['url' => '/clinicus/admin/user_type_pages?table=user_type_pages', 'icon' => 'bi-file-earmark', 'text' => 'User Type Pages'],
                        ['url' => '/clinicus/admin/words?table=words', 'icon' => 'bi-translate', 'text' => 'Words'],
                    ];

                    foreach ($menuItems as $item) {
                        $isActive = (isset($tableName) && strpos($item['url'], "table.php?table=$tableName") !== false) ? 'active' : '';
                        echo '<a href="' . $item['url'] . '" class="list-group-item list-group-item-action ' . $isActive . '">';
                        echo '<i class="bi ' . $item['icon'] . '"></i> ' . $item['text'];
                        echo '</a>';
                    }
                    ?>
                </div>
            </div>
            <!-- Notification system -->
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
                        <?php
                        $error = $_GET['error'];
                        if ($error === 'invalid_table') {
                            echo 'Invalid table name specified';
                        } else {
                            echo htmlspecialchars($error);
                        }
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php echo $content ?? ''; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>