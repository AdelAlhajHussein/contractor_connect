<?php

namespace App\Controllers\Homeowner;

use App\Controllers\BaseController;
use App\Models\ProjectModel;

class Dashboard extends BaseController
{
    public function index()
    {
        // Get table data
        $tableData = $this->_getProjectData();

        $data = [
            'title'        => "Homeowner Dashboard",
            'headers'      => $tableData['headers'],
            'project_rows' => $tableData['rows']
        ];

        return view('homeowner/dashboard', $data);
    }

    public function get_table($type)
    {
        if ($type === 'projects') {
            $data = $this->_getProjectData();

            return view('components/dashboard-table', [
                'headers' => $data['headers'],
                'rows'    => $data['rows']
            ]);
        }

        return "No data found for type: " . esc($type);
    }

    private function _getProjectData()
    {
        $projectModel = new ProjectModel();
        $projects = $projectModel->where('home_owner_id', 1)->findAll();

        $headers = ['Project Title', 'Budget Range', 'Deadline', 'Status', 'Actions'];
        $rows = [];

        foreach($projects as $project){
            $rows[] = [
                esc($project['title']),
                '$' . number_format($project['budget_min']) . ' - $' . number_format($project['budget_max']),
                $project['deadline_date'] ?? 'N/A',
                esc($project['status']),
                '<a href="' . site_url('homeowner/projects/view/' . $project['id']) . '" class="btn-view">View</a>'
            ];
        }
        return ['headers' => $headers, 'rows' => $rows];
    }
}