<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class PaymentsController extends BaseController
{
    public function index()
    {
        return view('admin/payments/index');
    }
}
