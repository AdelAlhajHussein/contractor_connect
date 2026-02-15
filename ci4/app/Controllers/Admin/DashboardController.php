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

        switch ($type) {

            // All users
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

                $contractors = $userModel->where('role_id', 3)->findAll();

                $data['headers'] = ['ID', 'Username', 'Email', 'Status'];

                if (!empty($contractors)) {
                    foreach ($contractors as $c) {
                        $data['rows'][] = [
                            'id'        => $c['id'],
                            'username'  => esc($c['username']),
                            'email'     => esc($c['email']),
                            'status'    => ($c['is_active'] == 1) ? 'Active' : 'Inactive'
                        ];
                    }
                } else {
                    $data['rows'] = [];
                }
                break;

            // Admin Reports
            case 'reports':
                // Summary info
                $userModel = new \App\Models\UserModel();
                $data['headers'] = ['Report Metric', 'Value'];
                $data['rows'] = [
                    ['Total Users', $userModel->countAll()],
                    ['Active Users', $userModel->where('is_active', 1)->countAll()],
                ];
                break;

            // Task categories
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
        }

        return view('components/dashboard-table', $data);
    }


}