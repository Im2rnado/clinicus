<?php
$title = "Patient History - Clinicus";
?>

<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Patient History</h2>
                <a href="javascript:history.back()" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>

    <!-- Patient Information -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Patient Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Name</label>
                            <p><?php echo htmlspecialchars($patient['firstName'] . ' ' . $patient['lastName']); ?></p>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Email</label>
                            <p><?php echo htmlspecialchars($patient['email']); ?></p>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Phone</label>
                            <p><?php echo htmlspecialchars($patient['phone']); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Medical Record -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Add Medical Record</h5>
                </div>
                <div class="card-body">
                    <form action="/clinicus/doctor/addMedicalHistory" method="POST">
                        <input type="hidden" name="patient_id" value="<?php echo $patient['ID']; ?>">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="service_id" class="form-label">Service Type</label>
                                <select class="form-select" id="service_id" name="service_id" required>
                                    <option value="">Select a service</option>
                                    <option value="1">Consultation</option>
                                    <option value="2">Follow-up</option>
                                    <option value="3">Emergency</option>
                                    <option value="4">Routine Check-up</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="appointment_id" class="form-label">Appointment</label>
                                <select class="form-select" id="appointment_id" name="appointment_id" required>
                                    <option value="">Select an appointment</option>
                                    <?php foreach ($appointments as $appointment): ?>
                                        <option value="<?php echo $appointment['ID']; ?>">
                                            <?php echo date('M d, Y h:i A', strtotime($appointment['appointmentDate'])); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="diagnosis" class="form-label">Diagnosis</label>
                            <textarea class="form-control" id="diagnosis" name="diagnosis" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="treatment" class="form-label">Treatment</label>
                            <textarea class="form-control" id="treatment" name="treatment" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Additional Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i> Add Record
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Medical History -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Medical History</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($medicalHistory)): ?>
                        <p class="text-muted">No medical history available.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Service Type</th>
                                        <th>Diagnosis</th>
                                        <th>Treatment</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($medicalHistory as $record): ?>
                                        <tr>
                                            <td><?php echo date('M d, Y', strtotime($record['date'])); ?></td>
                                            <td><?php echo htmlspecialchars($record['serviceType']); ?></td>
                                            <td><?php echo htmlspecialchars($record['diagnosis']); ?></td>
                                            <td><?php echo htmlspecialchars($record['treatment']); ?></td>
                                            <td><?php echo htmlspecialchars($record['notes']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>