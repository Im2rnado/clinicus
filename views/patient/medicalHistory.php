<?php
$title = "Medical History - Clinicus";
?>

<div class="container py-4">
    <div class="box">
        <h2 class="mb-4">Medical History</h2>

        <?php if (empty($history)): ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> No medical history records found.
            </div>
        <?php else: ?>
            <div class="accordion" id="medicalHistoryAccordion">
                <?php foreach ($history as $index => $record): ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading<?php echo $index; ?>">
                            <button class="accordion-button <?php echo $index === 0 ? '' : 'collapsed'; ?>" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $index; ?>">
                                <div class="d-flex justify-content-between align-items-center w-100 me-3">
                                    <span>
                                        <strong><?php echo date('M d, Y', strtotime($record['date'])); ?></strong>
                                        - <?php echo htmlspecialchars($record['diagnosis']); ?>
                                    </span>
                                    <span class="badge bg-primary">Dr.
                                        <?php echo htmlspecialchars($record['doctorName']); ?></span>
                                </div>
                            </button>
                        </h2>
                        <div id="collapse<?php echo $index; ?>"
                            class="accordion-collapse collapse <?php echo $index === 0 ? 'show' : ''; ?>"
                            data-bs-parent="#medicalHistoryAccordion">
                            <div class="accordion-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5>Diagnosis</h5>
                                        <p><?php echo nl2br(htmlspecialchars($record['diagnosis'])); ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <h5>Treatment</h5>
                                        <p><?php echo nl2br(htmlspecialchars($record['treatment'])); ?></p>
                                    </div>
                                </div>
                                <?php if (!empty($record['notes'])): ?>
                                    <div class="mt-3">
                                        <h5>Additional Notes</h5>
                                        <p><?php echo nl2br(htmlspecialchars($record['notes'])); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>