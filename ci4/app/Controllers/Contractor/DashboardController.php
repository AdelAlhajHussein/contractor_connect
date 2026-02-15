<?php

namespace App\Controllers\Contractor;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        $contractorId = session('user_id');

        return view('contractor/dashboard', [
            'contractorId' => $contractorId
        ]);
    }
}
