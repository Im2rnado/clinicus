<?php
$title = "My Messages - Clinicus";
?>

<div class="container py-4">
    <div class="box">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>My Messages</h2>
        </div>

        <?php if (empty($messages)): ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> You have no messages at the moment.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date & Time</th>
                            <th>Title</th>
                            <th>From</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($messages as $message): ?>
                            <tr>
                                <td><?php echo date('M d, Y h:i A', strtotime($message['created_at'])); ?></td>
                                <td><?php echo htmlspecialchars($message['title']); ?></td>
                                <td><?php echo htmlspecialchars($message['sender_name']); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $message['is_read'] ? 'secondary' : 'primary'; ?>">
                                        <?php echo $message['is_read'] ? 'Read' : 'New'; ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info" onclick="viewMessage(<?php echo $message['ID']; ?>)"
                                        data-bs-toggle="modal" data-bs-target="#messageModal">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Message Modal -->
<div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="messageModalLabel">Message Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="messageContent">
                    <!-- Message content will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    function viewMessage(messageId) {
        // TODO: Implement message viewing functionality
        // This will be implemented when we add the message viewing endpoint
        alert('Message viewing will be implemented soon.');
    }
</script>