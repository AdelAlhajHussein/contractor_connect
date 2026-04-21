<?php

namespace App\Controllers\Homeowner;

use App\Controllers\BaseController;

class ContractorController extends BaseController
{
    public function view($id)
    {
        $db = \Config\Database::connect();

        // Contractor info
        $contractor = $db->table('users')
            ->where('id', $id)
            ->get()
            ->getRowArray();


        return view(
            'homeowner/contractor/view',
            [
                'contractor' => $contractor
            ]
        );
    }
}