<?php

include_once __DIR__ . "/../Model/entities/User.php";

use Model\entities\User;

class Controller
{
    protected $db;
    protected $user;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->user = isset($_SESSION['user_id']) ? (new User($this->db))->getUserById($_SESSION['user_id']) : null;
    }

    protected function render($view, $data = [])
    {
        // Extract data to make variables available in view
        extract($data);

        // Start output buffering
        ob_start();

        // Include the view file
        require_once "views/{$view}.php";

        // Get the contents of the buffer
        $content = ob_get_clean();

        // If this is an AJAX request, just return the content
        if (
            !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
        ) {
            echo $content;
            return;
        }

        // Otherwise, wrap in layout
        require_once "views/layouts/main.php";
    }

    protected function redirect($url)
    {
        header("Location: {$url}");
        exit;
    }

    protected function isAuthenticated()
    {
        return isset($_SESSION['user_id']);
    }

    protected function requireAuth()
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('/clinicus/auth/login');
        }
    }

    protected function requireRole($role)
    {
        $this->requireAuth();

        if ($_SESSION['user_type'] !== $role) {
            $this->redirect('/clinicus/');
        }
    }

    protected function json($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function validateCSRF()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                throw new Exception('Invalid CSRF token');
            }
        }
    }

    protected function generateCSRFToken()
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}