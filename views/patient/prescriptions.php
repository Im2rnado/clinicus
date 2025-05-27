<?php
$title = "My Prescriptions - Clinicus";
?>

<div class="container py-4">
    <div class="box">
        <h2 class="mb-4">My Prescriptions</h2>

        <?php if (empty($prescriptions)): ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> No prescriptions found.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Medication</th>
                            <th>Dosage</th>
                            <th>Frequency</th>
                            <th>Duration</th>
                            <th>Prescribed By</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($prescriptions as $prescription): ?>
                            <tr>
                                <td><?php echo date('M d, Y', strtotime($prescription['date'])); ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($prescription['medication']); ?></strong>
                                    <?php if (!empty($prescription['notes'])): ?>
                                        <br>
                                        <small class="text-muted"><?php echo htmlspecialchars($prescription['notes']); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($prescription['dosage']); ?></td>
                                <td><?php echo htmlspecialchars($prescription['frequency']); ?></td>
                                <td><?php echo htmlspecialchars($prescription['duration']); ?></td>
                                <td>Dr. <?php echo htmlspecialchars($prescription['doctorName']); ?></td>
                                <td>
                                    <?php
                                    $statusClass = [
                                        'Active' => 'success',
                                        'Completed' => 'secondary',
                                        'Cancelled' => 'danger'
                                    ][$prescription['status']] ?? 'primary';
                                    ?>
                                    <span class="badge bg-<?php echo $statusClass; ?>">
                                        <?php echo htmlspecialchars($prescription['status']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>