<?php

class HomeController
{
    public function render($view, $data = [])
    {
        extract($data);

        ob_start();
        require_once "./$view.php";
        $content = ob_get_clean();

        require_once "./layout.php";
    }

    public function index()
    {
        $this->render('home', [
            'title' => 'Welcome'
        ]);
    }
}
