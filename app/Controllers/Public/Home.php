<?php

namespace App\Controllers\Public;

use App\Controllers\BaseController;

class Home extends BaseController
{
    public function index()
    {
        return view('public/index');
    }
}
