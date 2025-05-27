<?php
$title = 'Doctor Profile';
?>

<div class="container py-4">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <img src="<?php echo $doctor['image'] ?? '/assets/images/default-doctor.jpg'; ?>"
                        alt="Dr. <?php echo htmlspecialchars($doctor['FirstName'] . ' ' . $doctor['LastName']); ?>"
                        class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">

                    <h4 class="card-title mb-3">Dr.
                        <?php echo htmlspecialchars($doctor['FirstName'] . ' ' . $doctor['LastName']); ?>
                    </h4>

                    <div class="mb-3">
                        <div class="display-4 text-warning mb-2">
                            <?php echo number_format($doctor['rating'], 1); ?>
                        </div>
                        <div class="rating-stars-static">
                            <?php
                            $rating = round($doctor['rating']);
                            for ($i = 1; $i <= 5; $i++) {
                                $color = $i <= $rating ? '#ffd700' : '#ddd';
                                echo "<i class='fas fa-star' style='color: {$color};'></i>";
                            }
                            ?>
                        </div>
                        <a href="./ratings/view/<?php echo $doctor['ID']; ?>" class="text-muted mt-2 d-block">
                            View all reviews
                        </a>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Specialization</h6>
                        <p class="mb-0"><?php echo htmlspecialchars($doctor['Specialization']); ?></p>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Contact Information</h6>
                        <p class="mb-1">
                            <i class="fas fa-envelope text-primary me-2"></i>
                            <?php echo htmlspecialchars($doctor['Email']); ?>
                        </p>
                        <p class="mb-0">
                            <i class="fas fa-phone text-primary me-2"></i>
                            <?php echo htmlspecialchars($doctor['Phone']); ?>
                        </p>
                    </div>

                    <a href="./appointments/create?doctor=<?php echo $doctor['ID']; ?>" class="btn btn-primary">
                        <i class="fas fa-calendar-plus"></i> Book Appointment
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">About</h5>
                    <p class="card-text"><?php echo nl2br(htmlspecialchars($doctor['Bio'])); ?></p>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Education & Experience</h5>
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Education</h6>
                        <p class="mb-0"><?php echo nl2br(htmlspecialchars($doctor['Education'])); ?></p>
                    </div>
                    <div>
                        <h6 class="text-muted mb-2">Experience</h6>
                        <p class="mb-0"><?php echo nl2br(htmlspecialchars($doctor['Experience'])); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .rating-stars-static {
        font-size: 1.2rem;
    }

    .rating-stars-static i {
        margin: 0 0.1em;
    }
</style>