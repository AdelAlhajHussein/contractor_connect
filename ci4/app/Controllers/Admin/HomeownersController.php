<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class HomeownersController extends BaseController
{
    public function index()
    {
        return view('admin/homeowners/index');
    }
}
