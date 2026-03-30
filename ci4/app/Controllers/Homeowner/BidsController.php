<?php

namespace App\Controllers\Homeowner;

use App\Controllers\BaseController;

class BidsController extends BaseController
{
    public function index($projectId)
    {
        $userId = (int) session()->get('user_id');

        $db = \Config\Database::connect();

        $bids = $db->table('bids b')
            ->select('b.project_id, p.title, b.bid_amount, b.id AS bid_id, u.username AS contractor_name, b.status')
            ->join('projects p', 'p.id = b.project_id')
            ->join('users u', 'u.id = b.contractor_id')
            ->where('p.home_owner_id', $userId)
            ->where('b.project_id', (int) $projectId)
            ->orderBy('b.created_at', 'DESC')
            ->get()
            ->getResultArray();

        return view('homeowner/bids/index', ['bids' => $bids]);
    }


}
