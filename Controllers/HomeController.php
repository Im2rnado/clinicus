<?php

class HomeController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Welcome to Clinicus'
        ];
        $this->render('home/index', $data);
    }
}
