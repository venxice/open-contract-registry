<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $file = FCPATH . 'home_spa.html';
        if (file_exists($file)) {
            return $this->response->setBody(file_get_contents($file))
                ->setHeader('Content-Type', 'text/html');
        }
        return view('welcome_message');
    }
}
