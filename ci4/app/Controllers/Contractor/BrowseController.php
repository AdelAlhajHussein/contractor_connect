<?php

namespace App\Controllers\Contractor;

use App\Controllers\BaseController;

class BrowseController extends BaseController
{
    public function index()
    {
        $contractorId = session('user_id');
        $db = \Config\Database::connect();

        // Available projects = open projects that this contractor has NOT bid on yet
        $projects = $db->table('projects p')
            ->select('p.id as project_id, p.title, p.description, p.budget_min, p.budget_max, p.status')
            ->where('p.status', 'bidding_open')
            ->whereNotIn('p.id', function($builder) use ($contractorId) {
                return $builder->select('b.project_id')
                    ->from('bids b')
                    ->where('b.contractor_id', $contractorId);
            })
            ->orderBy('p.id', 'DESC')
            ->get()
            ->getResultArray();

        return view('contractor/browse/index', [
            'projects' => $projects
        ]);
    }

    public function details($projectId)
    {
        $db = \Config\Database::connect();

        $project = $db->table('projects')
            ->where('id', $projectId)
            ->get()
            ->getRowArray();

        if (!$project) {
            return redirect()->to(site_url('contractor/browse'))
                ->with('error', 'Project not found');
        }

        return view('contractor/browse/details', [
            'project' => $project
        ]);
    }

}
