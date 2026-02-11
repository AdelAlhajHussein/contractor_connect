<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;


class ContractorsController extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();

        $data['contractors'] = $userModel
            ->select('users.id, users.username, users.first_name, users.last_name, users.email, users.phone, users.is_active, users.created_at,
                  contractor_profiles.address, contractor_profiles.city, contractor_profiles.province, contractor_profiles.postal_code')
            ->join('contractor_profiles', 'contractor_profiles.contractor_id = users.id', 'left')
            ->where('users.role_id', 2)
            ->orderBy('users.id', 'DESC')
            ->findAll();

        return view('admin/contractors/index', $data);
    }

}
