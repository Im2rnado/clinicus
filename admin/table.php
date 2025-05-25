<?php
// admin/table.php
require_once '../Model/config.php';
require_once '../Model/autoload.php';

use Model\entities\ModelFactory;

// Get the table name from the URL
$tableName = $_GET['table'] ?? '';

// Get the model instance
$db = (new DatabaseConnection())->connectToDB();
if (empty($tableName)) {
    throw new Exception("No table specified in the URL (expected ?table=tablename)");
}
$model = Model\entities\ModelFactory::getModelInstance($tableName, $db);
$model = ModelFactory::getModelInstance($tableName, $db);

// Get the records
$records = $model->readAll();

// Get the column names
$columns = [];
$primaryKey = 'id';
if (!empty($records)) {
    $columns = array_keys($records[0]);
    if (!in_array('id', $columns)) {
        // Try to find a column that ends with 'id' (case-insensitive)
        foreach ($columns as $col) {
            if (preg_match('/id$/i', $col)) {
                $primaryKey = $col;
                break;
            }
        }
    }
}
?>

<div class="container">
    <h1>Manage <?php echo ucfirst($tableName); ?></h1>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php echo ucfirst($tableName); ?> List</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createModal">
                            Create New
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <?php foreach ($columns as $column): ?>
                                    <th><?php echo ucfirst($column); ?></th>
                                <?php endforeach; ?>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($records as $record): ?>
                                <tr>
                                    <?php foreach ($columns as $column): ?>
                                        <td><?php echo $record[$column]; ?></td>
                                    <?php endforeach; ?>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal"
                                            data-target="#editModal" data-id="<?php echo $record[$primaryKey]; ?>">
                                            Edit
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                            data-target="#deleteModal" data-id="<?php echo $record[$primaryKey]; ?>">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Create New <?php echo ucfirst($tableName); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/clinicus/admin/actions/create.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="table" value="<?php echo $tableName; ?>">
                    <?php foreach ($columns as $column): ?>
                        <?php if ($column !== 'id'): ?>
                            <div class="form-group">
                                <label for="<?php echo $column; ?>"><?php echo ucfirst($column); ?></label>
                                <input type="text" class="form-control" id="<?php echo $column; ?>"
                                    name="<?php echo $column; ?>" required>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit <?php echo ucfirst($tableName); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/clinicus/admin/actions/update.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="table" value="<?php echo $tableName; ?>">
                    <input type="hidden" name="id" id="edit-id">
                    <?php foreach ($columns as $column): ?>
                        <?php if ($column !== 'id'): ?>
                            <div class="form-group">
                                <label for="edit-<?php echo $column; ?>"><?php echo ucfirst($column); ?></label>
                                <input type="text" class="form-control" id="edit-<?php echo $column; ?>"
                                    name="<?php echo $column; ?>" required>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete <?php echo ucfirst($tableName); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this record?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" id="delete-button">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Edit button click
        $('.btn-info').click(function () {
            var id = $(this).data('id');
            $.get('/clinicus/admin/actions/read.php', { table: '<?php echo $tableName; ?>', id: id }, function (data) {
                if (data.success) {
                    var record = data.record;
                    $('#edit-id').val(record.id);
                    <?php foreach ($columns as $column): ?>
                        <?php if ($column !== 'id'): ?>
                            $('#edit-<?php echo $column; ?>').val(record.<?php echo $column; ?>);
                        <?php endif; ?>
                    <?php endforeach; ?>
                }
            });
        });

        // Delete button click
        $('.btn-danger').click(function () {
            var id = $(this).data('id');
            $('#delete-button').data('id', id);
        });

        // Delete confirmation
        $('#delete-button').click(function () {
            var id = $(this).data('id');
            $.ajax({
                url: '/clinicus/admin/actions/delete.php',
                type: 'DELETE',
                data: { table: '<?php echo $tableName; ?>', id: id },
                success: function (data) {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                }
            });
        });
    });
</script>