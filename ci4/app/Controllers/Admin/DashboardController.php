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
            'rows'    => []
        ];

        switch ($type) {
            case 'users':
                $model = new \App\Models\UserModel();
                $users = $model->findAll();

                $data['headers'] = ['ID', 'Username', 'Email', 'Role ID', 'Status'];
                foreach ($users as $user) {
                    $data['rows'][] = [
                        $user['id'],
                        esc($user['username']),
                        esc($user['email']),
                        $user['role_id'],
                        $user['is_active'] ? 'Active' : 'Inactive'
                    ];
                }
                break;

                //TODO: more cases
        }
        // This returns ONLY the table HTML from your component
        return view('components/dashboard-table', $data);
    }

}
