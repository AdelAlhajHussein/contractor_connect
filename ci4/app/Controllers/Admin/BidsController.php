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

    public function view($id)
    {
        $bidModel = new \App\Models\BidModel();
        $taskModel = new \App\Models\BidTaskModel();

        $bid = $bidModel->builder()
            ->select('bids.*, projects.title AS project_title, users.email AS contractor_email')
            ->join('projects', 'projects.id = bids.project_id', 'left')
            ->join('users', 'users.id = bids.contractor_id', 'left')
            ->where('bids.id', (int)$id)
            ->get()
            ->getRowArray();

        if (!$bid) {
            return redirect()->to(site_url('admin/bids'));
        }

        $tasks = $taskModel->where('bid_id', (int)$id)
            ->orderBy('task_order', 'ASC')
            ->findAll();

        // Totals from tasks (calculated, not trusting stored totals)
        $sumMinutes = 0;
        $sumMaterials = 0.0;
        $sumLabour = 0.0;
        $sumHst = 0.0;

        foreach ($tasks as $t) {
            $sumMinutes += (int)($t['est_minutes'] ?? 0);
            $sumMaterials += (float)($t['materials_cost'] ?? 0);
            $sumLabour += (float)($t['labour_cost'] ?? 0);
            $sumHst += (float)($t['hst_cost'] ?? 0);
        }

        $data = [
            'bid' => $bid,
            'tasks' => $tasks,
            'taskTotals' => [
                'minutes' => $sumMinutes,
                'materials' => $sumMaterials,
                'labour' => $sumLabour,
                'hst' => $sumHst,
                'total' => ($sumMaterials + $sumLabour + $sumHst),
            ],
        ];

        return view('admin/bids/view', $data);
    }

    public function withdraw($id)
    {
        $bidModel = new \App\Models\BidModel();

        $bid = $bidModel->find((int)$id);
        if (!$bid) {
            return redirect()->to(site_url('admin/bids'));
        }

        // Only allow withdraw if not already accepted/rejected
        if (in_array($bid['status'], ['accepted', 'rejected'], true)) {
            return redirect()->to(site_url('admin/bids/' . (int)$id));
        }

        $bidModel->update((int)$id, ['status' => 'withdrawn']);

        return redirect()->to(site_url('admin/bids/' . (int)$id));
    }


}
