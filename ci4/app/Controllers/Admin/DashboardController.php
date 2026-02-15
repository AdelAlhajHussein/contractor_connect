<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        $userModel = new \App\Models\UserModel();
        $data = [
            'title' => 'Admin Dashboard',
            'users' => $userModel->findAll(),
        ];
        return view('admin/dashboard', $data);
    }

    // Get table data for X user type
    public function get_table($type)
    {
        $data = [
            'headers' => [],
            'rows' => [],
            'type' => $type,
        ];

        // Category/Nav Types
        switch ($type) {
            // Categories
            case 'categories':
                $categoryModel = new \App\Models\CategoryModel();

                // Use Ajax filters if search used
                $search = $this->request->getGet('q');
                $visibility = $this->request->getGet('visibility');

                if ($search) {
                    $categoryModel->like('name', $search);
                }

                // Filter out inactive
                if ($visibility !== '' && $visibility !== null) {
                    $categoryModel->where('is_visible', $visibility);
                }

                $categories = $categoryModel->findAll();

                // Prepare data
                $data['type'] = 'categories';
                $data['headers'] = ['ID', 'Name', 'Visible', 'Actions'];
                foreach ($categories as $c) {
                    $data['rows'][] = [
                        'id'         => $c['id'],
                        'name'       => esc($c['name']),
                        'is_visible' => $c['is_visible'],
                    ];
                }
                break;

            // Users
            case 'users':
                $model = new \App\Models\UserModel();
                $users = $model->findAll();

                $data['headers'] = ['ID', 'Username', 'Name', 'Email', 'Role', 'Status', 'Created', 'Actions'];
                foreach ($users as $user) {
                    $data['rows'][] = [
                        'id'         => $user['id'],
                        'username'   => esc($user['username']),
                        'name'       => $user['first_name'] . ' ' . $user['last_name'],
                        'email'      => esc($user['email']),
                        'role_id'    => $user['role_id'],
                        'is_active'  => $user['is_active'],
                        'created_at' => $user['created_at'],
                        'actions'    => []
                    ];
                }
                break;

            // Homeowners
            case 'homeowners':
                $userModel = new \App\Models\UserModel();

                // Join with profiles to get city, province, and phone info
                $userModel->select('users.*, home_owner_profiles.city, home_owner_profiles.province, users.phone')
                    ->join('home_owner_profiles', 'home_owner_profiles.home_owner_id = users.id', 'left')
                    ->where('role_id', 2);

                // Apply AJAX filters
                $q = $this->request->getGet('q');
                $status = $this->request->getGet('status');

                if ($q) {
                    $userModel->groupStart()
                        ->like('username', $q)
                        ->orLike('first_name', $q)
                        ->orLike('last_name', $q)
                        ->orLike('email', $q)
                        ->orLike('city', $q)
                        ->groupEnd();
                }

                if ($status !== '' && $status !== null) {
                    $userModel->where('is_active', $status);
                }

                $homeowners = $userModel->findAll();

                $data['type'] = 'homeowners';
                $data['headers'] = ['ID', 'Username', 'Name', 'Email', 'City', 'Status', 'Actions'];
                foreach ($homeowners as $homeowner) {
                    $data['rows'][] = [
                        'id'         => $homeowner['id'],
                        'username'   => esc($homeowner['username']),
                        'name'       => esc(trim($homeowner['first_name'] . ' ' . $homeowner['last_name'])),
                        'email'      => esc($homeowner['email']),
                        'city'       => esc($homeowner['city'] ?? 'N/A'),
                        'is_active'  => $homeowner['is_active'],
                        'created_at' => $homeowner['created_at'],
                    ];
                }
                break;

            // Contractors
            case 'contractors':

                $userModel = new \App\Models\UserModel();
                $userModel->where('role_id', 3);

                $q = $this->request->getGet('q');
                if (!empty($q)) {
                    $userModel->groupStart()
                        ->like('username', $q)
                        ->orLike('email', $q)
                        ->groupEnd();
                }

                $status = $this->request->getGet('status');
                if ($status !== '' && $status !== null) {
                    $userModel->where('is_active', $status);
                }

                $homeowners = $userModel->findAll();

                $data['type'] = 'contractors';
                $data['headers'] = ['ID', 'Username', 'Email', 'Status', 'Actions'];
                $data['rows'] = [];


                foreach ($homeowners as $result) {
                    $data['rows'][] = [
                        'id'        => $result['id'],
                        'username'  => esc($result['username']),
                        'email'     => esc($result['email']),
                        'is_active' => $result['is_active'],
                    ];
                }
                break;

            // Admin Reports
            case 'reports':
                // Models
                $userModel = new \App\Models\UserModel();
                $projectModel = new \App\Models\ProjectModel();
                $bidModel = new \App\Models\BidModel();

                $data['type'] = 'reports';
                $data['headers'] = ['Category', 'Metric', 'Count/Value', 'Status'];
                $data['rows'] = [
                    // User metrics
                    ['cat' => 'Users', 'metric' => 'Total Registered', 'val' => $userModel->countAll(), 'stat' => 'Global'],
                    ['cat' => 'Users', 'metric' => 'Contractors', 'val' => $userModel->where('role_id', 3)->countAll(), 'stat' => 'Active'],

                    // Project metrics
                    ['cat' => 'Projects', 'metric' => 'Total Projects', 'val' => $projectModel->countAll(), 'stat' => 'All Time'],
                    ['cat' => 'Projects', 'metric' => 'Completed', 'val' => $projectModel->where('status', 'completed')->countAll(), 'stat' => 'Finalized'],

                    // Bid metrics
                    ['cat' => 'Bids', 'metric' => 'Total Bids Submitted', 'val' => $bidModel->countAll(), 'stat' => 'Pending/Accepted'],
                    ['cat' => 'Bids', 'metric' => 'Total Volume ($)', 'val' => '$' . number_format($bidModel->selectSum('total_cost')->get()->getRow()->total_cost, 2), 'stat' => 'Revenue']
                ];
                break;

            // Projects
            case 'projects':
                $projectModel = new \App\Models\ProjectModel();

                // Join with Homeowner and Categories
                $projectModel->select('projects.*, users.first_name, users.last_name, categories.name as category_name')
                    ->join('users', 'users.id = projects.home_owner_id', 'left')
                    ->join('categories', 'categories.id = projects.category_id', 'left');

                $q = $this->request->getGet('q');
                $status = $this->request->getGet('status');

                if ($q) {
                    $projectModel->like('projects.title', $q);
                }

                if (!empty($status)) {
                    $projectModel->where('projects.status', $status);
                }

                $projects = $projectModel->findAll();

                $data['type'] = 'projects';
                $data['headers'] = ['ID', 'Title', 'Homeowner', 'Status', 'Budget', 'Deadline', 'Actions'];
                foreach ($projects as $project) {
                    $data['rows'][] = [
                        'id'        => $project['id'],
                        'title'     => esc($project['title']),
                        'homeowner' => esc(trim($project['first_name'] . ' ' . $project['last_name'])),
                        'status'    => $project['status'],
                        'budget'    => $project['budget_min'] . ' - ' . $project['budget_max'],
                        'deadline'  => $project['deadline_date'],
                        'actions'   => ''
                    ];
                }
                break;

            // Bids
            case 'bids':
                $bidModel = new \App\Models\BidModel();

                // Join Projects and Users tables
                $bidModel->select('bids.*, projects.title as project_title, users.email as contractor_email')
                    ->join('projects', 'projects.id = bids.project_id', 'left')
                    ->join('users', 'users.id = bids.contractor_id', 'left');

                $q = $this->request->getGet('q');
                $status = $this->request->getGet('status');

                if ($q) {
                    $bidModel->groupStart()
                        ->like('projects.title', $q)
                        ->orLike('users.email', $q)
                        ->groupEnd();
                }

                if (!empty($status)) {
                    $bidModel->where('bids.status', $status);
                }

                $results = $bidModel->findAll();

                $data['type'] = 'bids';
                $data['headers'] = ['ID', 'Project', 'Contractor', 'Status', 'Bid Amount', 'Total Cost', 'Actions'];
                foreach ($results as $b) {
                    $data['rows'][] = [
                        'id'               => $b['id'],
                        'project_title'    => esc($b['project_title'] ?? 'N/A'),
                        'contractor_email' => esc($b['contractor_email'] ?? 'N/A'),
                        'status'           => $b['status'],
                        'bid_amount'       => $b['bid_amount'],
                        'total_cost'       => $b['total_cost'],
                    ];
                }
                break;



        }

        return view('components/dashboard-table', $data);
    }
}