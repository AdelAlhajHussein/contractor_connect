<?php

namespace App\Controllers\Homeowner;

use App\Controllers\BaseController;
use App\Models\UserModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $userId = session('user_id');

        $userModel = model(UserModel::class);
        $user = $userModel->find((int) $userId);

        $data = [
            'user'    => $user,
            'profile' => [
                'first_name' => $user['first_name'] ?? '',
                'last_name'  => $user['last_name'] ?? '',
                'address'    => '',
                'payment_info' => '',
            ],
        ];

        return view('homeowner/dashboard', $data);
    }

}
