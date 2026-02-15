<?php

namespace App\Controllers\Contractor;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        return view('contractor/dashboard');
    }
}