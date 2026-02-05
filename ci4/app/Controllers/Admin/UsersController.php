<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UsersController extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();

        $users = $userModel
            ->orderBy('id', 'DESC')
            ->findAll(50);

        return view('admin/users/index', ['users' => $users]);
    }
}
