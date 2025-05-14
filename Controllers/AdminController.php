<?php

require_once "../Model/read.php";

class AdminController
{
    private $readModel;

    public function __construct()
    {
        $this->readModel = new ReadClass();
        $GLOBALS['readModel'] = $this->readModel; // Make it available globally
    }

    protected function renderAdmin($view, $data = [])
    {
        extract($data);
 
        ob_start(); // ob is used to start capturing would normally be printed to the screen
        require_once "../$view.php"; // loads the view file and captures its output instead of displaying
        $content = ob_get_clean(); // stores everyhting in content

        require_once "../admin/layout.php"; // loads layout and automatically inserts the content in it
    }

    public function index()
    {
        $this->renderAdmin('admin/dashboard', [
            'title' => 'Dashboard'
        ]);
    }

    public function manageTable($tableName)
    {
        try {
            // Get table data
            $records = $this->readModel->readAll($tableName);

            // Get column names (from first record)
            $columns = [];
            if (!empty($records)) {
                $columns = array_keys($records[0]);
            }

            $this->renderAdmin('admin/table', [
                'title' => 'Edit ' . $tableName,
                'tableName' => $tableName,
                'records' => $records,
                'columns' => $columns
            ]);

        } catch (Exception $e) {
            // echo error
            echo 'Error has occured' . $e->getMessage();
        }
    }
}
