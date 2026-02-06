<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class ContractorsController extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();

        $contractors = $userModel
            ->select('id, username, first_name, last_name, email, phone, is_active, created_at')
            ->where('role_id', 3)
            ->where('deleted_at', null)
            ->orderBy('id', 'DESC')
            ->findAll();

        return view('admin/contractors/index', [
            'contractors' => $contractors
        ]);
    }
}
