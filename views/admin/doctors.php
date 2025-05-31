<?php
$title = "Manage Doctors - Clinicus";
?>

<div class="container py-4">
    <div class="box">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Manage Doctors</h2>
            <a href="/clinicus/admin/doctors/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Doctor
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
                        <th>Specialization</th>
                        <th>Experience</th>
                        <th>Rating</th>
                        <th>Consultation Fee</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($doctors as $doctor): ?>
                        <tr>
                            <td><?php echo $doctor['ID']; ?></td>
                            <td>Dr. <?php echo htmlspecialchars($doctor['FirstName'] . ' ' . $doctor['LastName']); ?></td>
                            <td><?php echo htmlspecialchars($doctor['specialization']); ?></td>
                            <td><?php echo $doctor['yearsOfExperince']; ?> years</td>
                            <td>
                                <i class="fas fa-star text-warning"></i>
                                <?php echo number_format($doctor['rating'], 1); ?>
                            </td>
                            <td>$<?php echo number_format($doctor['consultation_fee'], 2); ?></td>
                            <td>
                                <a href="/clinicus/admin/doctors/edit/<?php echo $doctor['ID']; ?>"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="/clinicus/admin/doctors/view/<?php echo $doctor['ID']; ?>"
                                    class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                    onclick="confirmDelete(<?php echo $doctor['ID']; ?>)">
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
    function confirmDelete(doctorId) {
        if (confirm('Are you sure you want to delete this doctor?')) {
            window.location.href = `/clinicus/admin/doctors/delete/${doctorId}`;
        }
    }
</script>