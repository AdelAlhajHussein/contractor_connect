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

                $results = $userModel->findAll();

                $data['type'] = 'contractors';
                $data['headers'] = ['ID', 'Username', 'Email', 'Status', 'Actions'];
                $data['rows'] = [];


                foreach ($results as $result) {
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
        }

        return view('components/dashboard-table', $data);
    }
}