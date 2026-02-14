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

    public function view($id)
    {
        $projectModel = new ProjectModel();

        $project = $projectModel
            ->select('projects.*,
                  categories.name AS category_name,
                  users.username AS homeowner_username, users.first_name AS homeowner_first_name, users.last_name AS homeowner_last_name, users.email AS homeowner_email, users.phone AS homeowner_phone')
            ->join('categories', 'categories.id = projects.category_id', 'left')
            ->join('users', 'users.id = projects.home_owner_id', 'left')
            ->where('projects.id', $id)
            ->first();

        if (!$project) {
            return redirect()->to(site_url('admin/projects'));
        }

        return view('admin/projects/view', ['project' => $project]);
    }

    public function cancel($id)
    {
        $projectModel = new ProjectModel();

        $project = $projectModel->find($id);
        if (!$project) {
            return redirect()->to(site_url('admin/projects'));
        }

        // Cancel unless already completed
        if ($project['status'] !== 'completed') {
            $projectModel->update($id, ['status' => 'cancelled']);
        }

        return redirect()->to(site_url('admin/projects'));
    }

    public function closeBidding($id)
    {
        $projectModel = new ProjectModel();

        $project = $projectModel->find($id);
        if (!$project) {
            return redirect()->to(site_url('admin/projects'));
        }

        // Only valid when bidding is open
        if ($project['status'] === 'bidding_open') {
            // Force close bidding -> move to in_progress
            $projectModel->update($id, ['status' => 'in_progress']);
        }

        return redirect()->to(site_url('admin/projects'));
    }

}
