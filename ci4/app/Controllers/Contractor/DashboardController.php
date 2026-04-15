<?php

namespace App\Controllers\Contractor;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        $contractorId = session('user_id');

        $db = \Config\Database::connect();

        $user = $db->table('users')
            ->select('id, username, first_name, last_name, email, phone')
            ->where('id', $contractorId)
            ->get()
            ->getRowArray();

        return view('contractor/dashboard', [
            'contractorId'   => $contractorId,
            'user'           => $user,
            'profileAddress' => null,
        ]);
    }

}
