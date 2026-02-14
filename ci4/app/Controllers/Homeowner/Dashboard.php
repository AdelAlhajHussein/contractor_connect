<?php

namespace App\Controllers\Homeowner;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        return view('homeowner/dashboard');
    }
}