<?php

namespace App\Controllers\Homeowner;

use App\Controllers\BaseController;
use App\Models\UserModel;

class ProfileController extends BaseController
{
    public function index()
    {
        $userId = (int) session()->get('user_id');

        $userModel = new UserModel();

        $user = $userModel->find($userId);

        return view('homeowner/profile/index', [
            'user' => $user
        ]);
    }
}
