<?php
$title = "Manage Appointments - Clinicus";
?>

<div class="container py-4">
    <div class="box">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Manage Appointments</h2>
            <a href="/clinicus/admin/appointments/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Schedule New Appointment
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
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Date & Time</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appointments as $appointment): ?>
                        <tr>
                            <td><?php echo $appointment['ID']; ?></td>
                            <td><?php echo htmlspecialchars($appointment['patientName']); ?></td>
                            <td>Dr. <?php echo htmlspecialchars($appointment['doctorName']); ?></td>
                            <td>
                                <?php
                                $date = new DateTime($appointment['appointmentDate']);
                                echo $date->format('M d, Y h:i A');
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($appointment['reason']); ?></td>
                            <td>
                                <span class="badge bg-<?php echo $appointment['statusColor']; ?>">
                                    <?php echo $appointment['status']; ?>
                                </span>
                            </td>
                            <td>
                                <a href="/clinicus/admin/appointments/edit/<?php echo $appointment['ID']; ?>"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="/clinicus/admin/appointments/view/<?php echo $appointment['ID']; ?>"
                                    class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                    onclick="confirmDelete(<?php echo $appointment['ID']; ?>)">
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
    function confirmDelete(appointmentId) {
        if (confirm('Are you sure you want to delete this appointment?')) {
            window.location.href = `/clinicus/admin/appointments/delete/${appointmentId}`;
        }
    }
</script>