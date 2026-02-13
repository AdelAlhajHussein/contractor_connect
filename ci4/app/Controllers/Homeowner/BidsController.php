<?php

namespace App\Controllers\Homeowner;

use App\Controllers\BaseController;

class BidsController extends BaseController
{
    public function index()
    {
        $userId = (int) session('user_id');

        $builder = db_connect()->table('bids b')
            ->select('b.id AS bid_id, p.id AS project_id, p.title, b.bid_amount, b.status, u.first_name, u.last_name')
            ->join('projects p', 'p.id = b.project_id')
            ->join('users u', 'u.id = b.contractor_id')
            ->where('p.home_owner_id', $userId)
            ->orderBy('b.id', 'DESC');

        return view('homeowner/bids/index', [
            'bids' => $builder->get()->getResultArray(),
        ]);
    }

}
