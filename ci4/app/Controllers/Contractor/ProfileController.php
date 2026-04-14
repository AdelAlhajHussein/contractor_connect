<?php

namespace App\Controllers\Contractor;

use App\Controllers\BaseController;

class ProfileController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $userId = (int) session()->get('user_id');

        $profile = $db->table('users u')
            ->select('u.username, u.first_name, u.last_name, u.email, u.phone, cp.address, cp.city, cp.province, cp.postal_code, cp.approval_status')
            ->join('contractor_profiles cp', 'cp.contractor_id = u.id', 'left')
            ->where('u.id', $userId)
            ->get()
            ->getRow();

        return view('contractor/profile/index', [
            'profile' => $profile
        ]);
    }
}