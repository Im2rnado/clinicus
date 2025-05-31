<?php
$title = "Manage Users - Clinicus";
?>

<div class="container py-4">
    <div class="box">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Manage Users</h2>
            <a href="/clinicus/admin/users/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New User
            </a>
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

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['userID']; ?></td>
                            <td><?php echo htmlspecialchars($user['FirstName'] . ' ' . $user['LastName']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td>
                                <span class="badge bg-<?php echo $user['roleID'] === 1 ? 'danger' :
                                    ($user['roleID'] === 2 ? 'primary' :
                                        ($user['roleID'] === 3 ? 'info' : 'success')); ?>">
                                    <?php
                                    switch ($user['roleID']) {
                                        case 1:
                                            echo 'Admin';
                                            break;
                                        case 2:
                                            echo 'Doctor';
                                            break;
                                        case 3:
                                            echo 'Patient';
                                            break;
                                        default:
                                            echo 'Unknown';
                                    }
                                    ?>
                                </span>
                            </td>
                            <td>
                                <a href="/clinicus/admin/users/edit/<?php echo $user['userID']; ?>"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="/clinicus/admin/users/view/<?php echo $user['userID']; ?>"
                                    class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                    onclick="confirmDelete(<?php echo $user['userID']; ?>)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function confirmDelete(userId) {
        if (confirm('Are you sure you want to delete this user?')) {
            window.location.href = `/clinicus/admin/users/delete/${userId}`;
        }
    }
</script>