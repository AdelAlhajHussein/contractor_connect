<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class BidsController extends BaseController
{
    public function index()
    {
        return view('admin/bids/index');
    }
}
