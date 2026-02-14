<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class ReportsController extends BaseController
{
    public function index()
    {
        $db = db_connect();

        // Users
        $totalUsers = (int) $db->table('users')->countAllResults();

        $usersByRole = $db->table('users')
            ->select('role_id, COUNT(*) AS total')
            ->groupBy('role_id')
            ->get()
            ->getResultArray();

        // Projects
        $totalProjects = (int) $db->table('projects')->countAllResults();

        $projectsByStatus = $db->table('projects')
            ->select('status, COUNT(*) AS total')
            ->groupBy('status')
            ->get()
            ->getResultArray();

        // Bids
        $totalBids = (int) $db->table('bids')->countAllResults();

        $bidsByStatus = $db->table('bids')
            ->select('status, COUNT(*) AS total')
            ->groupBy('status')
            ->get()
            ->getResultArray();

        return view('admin/reports/index', [
            'totalUsers'       => $totalUsers,
            'usersByRole'      => $usersByRole,
            'totalProjects'    => $totalProjects,
            'projectsByStatus' => $projectsByStatus,
            'totalBids'        => $totalBids,
            'bidsByStatus'     => $bidsByStatus,
        ]);
    }
}
