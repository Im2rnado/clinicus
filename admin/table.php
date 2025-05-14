<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4><?php echo $tableName ?></h4>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            Add New
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <?php foreach ($columns as $column): ?>
                            <th><?php echo $column ?></th>
                        <?php endforeach; ?>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($records as $record): ?>
                        <tr>
                            <?php foreach ($columns as $column): ?>
                                <td>
                                    <?php if ($column === 'Password'): ?>
                                        ••••••••••
                                    <?php else: ?>
                                        <?php echo htmlspecialchars($record[$column]); ?>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>
                            <td>
                                <button class="btn btn-sm btn-primary edit-btn" data-bs-toggle="modal"
                                    data-bs-target="#editModal" data-id="<?php echo $record['Id'] ?>">Edit</button>
                                <button class="btn btn-sm btn-danger delete-btn"
                                    data-id="<?php echo $record['Id'] ?>">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New <?php echo $tableName ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addRecordForm" method="post" action="/clinicus/admin/actions/create.php">
                <input type="hidden" name="table" value="<?php echo $tableName; ?>">
                <div class="modal-body">
                    <?php
                    foreach ($columns as $column):
                        if ($column !== 'Id'):
                            // Convert column name to a valid field name for the data array
                            $fieldName = lcfirst($column);
                            ?>
                            <div class="mb-3">
                                <label for="add_<?php echo $fieldName; ?>" class="form-label"><?php echo $column; ?></label>
                                <?php
                                // Special handling for foreign key fields
                                if ($column === 'Role'):
                                    $userTypes = $GLOBALS['readModel']->readAll('usertype');
                                    ?>
                                    <select class="form-select" id="add_<?php echo $fieldName; ?>" name="<?php echo $fieldName; ?>">
                                        <option value="">Select Role</option>
                                        <?php foreach ($userTypes as $userType): ?>
                                            <option value="<?php echo $userType['Id']; ?>"><?php echo $userType['UserType']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php elseif (stripos($column, 'DOB') !== false): ?>
                                    <input type="date" class="form-control" id="add_<?php echo $fieldName; ?>"
                                        name="<?php echo $fieldName; ?>" pattern="\d{4}-\d{2}-\d{2}" placeholder="yyyy-mm-dd"
                                        title="Date format: yyyy-mm-dd">
                                <?php elseif (stripos($column, 'Password') !== false): ?>
                                    <input type="password" class="form-control" id="add_<?php echo $fieldName; ?>"
                                        name="<?php echo $fieldName; ?>">
                                <?php elseif (stripos($column, 'Email') !== false): ?>
                                    <input type="email" class="form-control" id="add_<?php echo $fieldName; ?>"
                                        name="<?php echo $fieldName; ?>">
                                <?php else: ?>
                                    <input type="text" class="form-control" id="add_<?php echo $fieldName; ?>"
                                        name="<?php echo $fieldName; ?>">
                                <?php endif; ?>
                            </div>
                            <?php
                        endif;
                    endforeach;
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Record</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit <?php echo $tableName ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editRecordForm" method="post" action="/clinicus/admin/actions/update.php">
                <input type="hidden" name="table" value="<?php echo $tableName; ?>">
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-body">
                    <?php foreach ($columns as $column): ?>
                        <?php if ($column !== 'Id'): ?>
                            <div class="mb-3">
                                <label for="edit_<?php echo $column; ?>" class="form-label"><?php echo $column ?></label>
                                <?php if ($column === 'Role'): ?>
                                    <input type="text" class="form-control" id="edit_<?php echo $column; ?>"
                                        name="<?php echo strtolower($column); ?>" readonly>
                                <?php else: ?>
                                    <input type="text" class="form-control" id="edit_<?php echo $column; ?>"
                                        name="<?php echo strtolower($column); ?>">
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Script to handle record deletion
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function () {
            if (confirm('Are you sure you want to delete this record?')) {
                const id = this.getAttribute('data-id');
                const tableName = '<?php echo $tableName; ?>';

                fetch(`/clinicus/admin/actions/delete.php?table=${tableName}&id=${id}`, {
                    method: 'DELETE'
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Record deleted successfully');
                            location.reload();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while deleting the record');
                    });
            }
        });
    });

    // Script to handle edit button clicks
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const tableName = '<?php echo $tableName; ?>';

            // Fetch record data for editing
            fetch(`/clinicus/admin/actions/read.php?table=${tableName}&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.record) {
                        const record = data.record;
                        document.getElementById('edit_id').value = id;

                        Object.keys(record).forEach(key => {
                            const input = document.getElementById(`edit_${key}`);
                            if (input) {
                                if (input.tagName === 'SELECT') {
                                    input.value = record[key];
                                } else {
                                    input.value = record[key];
                                }
                            }
                        });
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while fetching the record');
                });
        });
    });
</script>