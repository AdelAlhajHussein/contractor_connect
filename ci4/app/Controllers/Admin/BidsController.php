<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BidModel;

class BidsController extends BaseController
{
    public function index()
    {
        $status = strtolower((string) $this->request->getGet('status'));
        $q      = trim((string) $this->request->getGet('q'));

        $allowedStatuses = ['submitted', 'accepted', 'rejected', 'withdrawn'];

        $bidModel = new \App\Models\BidModel();

        $builder = $bidModel->builder()
            ->select('bids.*, projects.title AS project_title, users.email AS contractor_email')
            ->join('projects', 'projects.id = bids.project_id', 'left')
            ->join('users', 'users.id = bids.contractor_id', 'left')
            ->orderBy('bids.created_at', 'DESC');

        // Status filter
        if ($status && in_array($status, $allowedStatuses, true)) {
            $builder->where('bids.status', $status);
        }

        // Text search filter (project title OR contractor email)
        if ($q !== '') {
            $builder->groupStart()
                ->like('projects.title', $q)
                ->orLike('users.email', $q)
                ->groupEnd();
        }

        $data = [
            'status'          => $status,
            'q'               => $q,
            'allowedStatuses' => $allowedStatuses,
            'bids'            => $builder->get()->getResultArray(),
        ];

        return view('admin/bids/index', $data);
    }

}
