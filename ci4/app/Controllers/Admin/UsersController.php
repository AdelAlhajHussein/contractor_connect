<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UsersController extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();

        $data['users'] = $userModel
            ->select('id, username, first_name, last_name, email, role_id, is_active, created_at')
            ->findAll();

        return view('admin/users/index', $data);
    }

}
