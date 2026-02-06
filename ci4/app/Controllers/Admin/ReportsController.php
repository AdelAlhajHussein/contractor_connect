<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class ReportsController extends BaseController
{
    public function index()
    {
        return view('admin/reports/index');
    }
}
