<?php
$title = 'Rate Doctor';
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Rate Your Experience</h4>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h5>Appointment Details</h5>
                        <p class="mb-1">
                            <strong>Doctor:</strong> Dr. <?php echo htmlspecialchars($appointment['doctorName']); ?>
                        </p>
                        <p class="mb-1">
                            <strong>Date:</strong>
                            <?php echo date('F j, Y', strtotime($appointment['appointmentDate'])); ?>
                        </p>
                        <p class="mb-0">
                            <strong>Time:</strong>
                            <?php echo date('g:i A', strtotime($appointment['appointmentDate'])); ?>
                        </p>
                    </div>

                    <form method="POST" action="./create/<?php echo $appointment['ID']; ?>">
                        <?php if (isset($errors['system'])): ?>
                            <div class="alert alert-danger">
                                <?php echo $errors['system']; ?>
                            </div>
                        <?php endif; ?>

                        <div class="mb-4">
                            <label class="form-label">Your Rating</label>
                            <div class="rating">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <input type="radio" name="rating" value="<?php echo $i; ?>" id="star<?php echo $i; ?>"
                                        <?php echo (isset($rating) && $rating == $i) ? 'checked' : ''; ?>>
                                    <label for="star<?php echo $i; ?>">☆</label>
                                <?php endfor; ?>
                            </div>
                            <?php if (isset($errors['rating'])): ?>
                                <div class="invalid-feedback d-block">
                                    <?php echo $errors['rating']; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-4">
                            <label for="comment" class="form-label">Your Review</label>
                            <textarea class="form-control" id="comment" name="comment" rows="4"
                                placeholder="Share your experience with this doctor..."><?php echo $comment ?? ''; ?></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-star"></i> Submit Rating
                            </button>
                            <a href="./appointments" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
    }

    .rating input {
        display: none;
    }

    .rating label {
        font-size: 2rem;
        color: #ddd;
        cursor: pointer;
        padding: 0 0.1em;
    }

    .rating input:checked~label,
    .rating label:hover,
    .rating label:hover~label {
        color: #ffd700;
    }
</style>