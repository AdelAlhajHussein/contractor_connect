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

    // Show bid form for a project
    public function create($projectId)
    {
        $db = \Config\Database::connect();

        $project = $db->table('projects')
            ->where('id', $projectId)
            ->get()
            ->getRowArray();

        if (!$project) {
            return redirect()->to(site_url('contractor/browse'))->with('error', 'Project not found');
        }

        return view('contractor/bids/create', [
            'project' => $project
        ]);
    }

    // Handle bid submit
    public function store($projectId)
    {
        $contractorId = session('user_id');
        $db = \Config\Database::connect();

        $bidAmount = (float) $this->request->getPost('bid_amount');
        $details   = trim((string) $this->request->getPost('details'));

        // prevent duplicate bid by same contractor for same project
        $exists = $db->table('bids')
            ->where('project_id', $projectId)
            ->where('contractor_id', $contractorId)
            ->countAllResults();

        if ($exists > 0) {
            return redirect()->to(site_url('contractor/browse/' . $projectId))
                ->with('error', 'You already placed a bid for this project.');
        }

        $db->table('bids')->insert([
            'project_id'    => $projectId,
            'contractor_id' => $contractorId,
            'details'       => $details,
            'bid_amount'    => $bidAmount,
            'total_cost'    => $bidAmount, // phase 1: total = bid amount
            // status defaults to 'submitted'
        ]);

        return redirect()->to(site_url('contractor/bids'))
            ->with('success', 'Bid submitted successfully.');
    }
}
