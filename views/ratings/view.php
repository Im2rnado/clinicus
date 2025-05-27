<?php
$title = 'Doctor Ratings';
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Dr. <?php echo htmlspecialchars($doctor['name']); ?> - Ratings & Reviews</h4>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="display-4 text-warning mb-2">
                            <?php echo number_format($doctor['averageRating'], 1); ?>
                        </div>
                        <div class="rating-stars mb-2">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i
                                    class="fas fa-star <?php echo $i <= round($doctor['averageRating']) ? 'text-warning' : 'text-muted'; ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <p class="text-muted mb-0">
                            Based on <?php echo count($ratings); ?> reviews
                        </p>
                    </div>

                    <div class="mb-4">
                        <h5>Rating Distribution</h5>
                        <?php
                        $ratingCounts = array_count_values(array_column($ratings, 'rating'));
                        for ($i = 5; $i >= 1; $i--):
                            $count = $ratingCounts[$i] ?? 0;
                            $percentage = count($ratings) > 0 ? ($count / count($ratings)) * 100 : 0;
                            ?>
                            <div class="d-flex align-items-center mb-2">
                                <div class="me-2" style="width: 60px;">
                                    <?php echo $i; ?> <i class="fas fa-star text-warning"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-warning" role="progressbar"
                                            style="width: <?php echo $percentage; ?>%"></div>
                                    </div>
                                </div>
                                <div class="ms-2" style="width: 40px;">
                                    <?php echo $count; ?>
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>

                    <?php if (empty($ratings)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> No ratings yet.
                        </div>
                    <?php else: ?>
                        <div class="reviews">
                            <?php foreach ($ratings as $rating): ?>
                                <div class="review mb-4">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 class="mb-0"><?php echo htmlspecialchars($rating['userName']); ?></h6>
                                            <small class="text-muted">
                                                <?php echo date('F j, Y', strtotime($rating['createdAt'])); ?>
                                            </small>
                                        </div>
                                        <div class="rating-stars">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i
                                                    class="fas fa-star <?php echo $i <= $rating['rating'] ? 'text-warning' : 'text-muted'; ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    <?php if (!empty($rating['comment'])): ?>
                                        <p class="mb-0"><?php echo nl2br(htmlspecialchars($rating['comment'])); ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .rating-stars {
        display: inline-block;
    }

    .rating-stars i {
        font-size: 1.2rem;
        margin: 0 0.1rem;
    }

    .review {
        padding: 1rem;
        background-color: #f8f9fa;
        border-radius: 0.5rem;
    }

    .review:last-child {
        margin-bottom: 0 !important;
    }
</style>