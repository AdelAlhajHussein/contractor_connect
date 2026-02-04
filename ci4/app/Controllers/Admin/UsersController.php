<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class UsersController extends BaseController
{
    public function index()
    {
        return view('admin/users/index');
    }
}
