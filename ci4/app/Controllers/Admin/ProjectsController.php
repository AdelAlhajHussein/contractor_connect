<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProjectModel;

class ProjectsController extends BaseController
{
    public function index()
    {
        $projectModel = new ProjectModel();

        $q      = trim($this->request->getGet('q') ?? '');
        $status = $this->request->getGet('status');

        $builder = $projectModel
            ->select('projects.id, projects.title, projects.status, projects.deadline_date, projects.budget_min, projects.budget_max, projects.created_at,
                      categories.name AS category_name,
                      users.username AS homeowner_username, users.first_name AS homeowner_first_name, users.last_name AS homeowner_last_name')
            ->join('categories', 'categories.id = projects.category_id', 'left')
            ->join('users', 'users.id = projects.home_owner_id', 'left');

        // Search (title)
        if ($q !== '') {
            $builder->like('projects.title', $q);
        }

        // Status filter
        if ($status !== null && $status !== '') {
            $builder->where('projects.status', $status);
        }

        $data['projects'] = $builder->orderBy('projects.id', 'DESC')->findAll();

        return view('admin/projects/index', $data);
    }
}
