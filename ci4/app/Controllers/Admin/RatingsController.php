<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class RatingsController extends BaseController
{
    public function index()
    {
        return view('admin/ratings/index');
    }
}
