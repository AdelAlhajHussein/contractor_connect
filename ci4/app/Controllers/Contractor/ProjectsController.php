<?php

namespace App\Controllers\Contractor;

use App\Controllers\BaseController;

class ProjectsController extends BaseController
{
    public function index()
    {
        $contractorId = session('user_id');
        $db = \Config\Database::connect();


        $myProjects = $db->table('bids b')
            ->select('p.id as project_id, p.title, p.start_date, p.end_date, p.status as project_status, b.bid_amount')
            ->join('projects p', 'p.id = b.project_id')
            ->where('b.contractor_id', $contractorId)
            ->orderBy('p.id', 'DESC')
            ->get()
            ->getResultArray();

        return view('contractor/projects/index', [
            'myProjects' => $myProjects
        ]);
    }
}
