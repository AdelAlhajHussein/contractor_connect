<?php

namespace App\Controllers\Contractor;

use App\Controllers\BaseController;

class BidsController extends BaseController
{
    public function index()
    {
        $contractorId = session('user_id');
        $db = \Config\Database::connect();

        $bids = $db->table('bids b')
            ->select('b.id as bid_id, b.project_id, b.bid_amount, b.status as bid_status, p.title as project_title, p.status as project_status')
            ->join('projects p', 'p.id = b.project_id')
            ->where('b.contractor_id', $contractorId)
            ->orderBy('b.id', 'DESC')
            ->get()
            ->getResultArray();

        return view('contractor/bids/index', [
            'bids' => $bids
        ]);
    }
}
