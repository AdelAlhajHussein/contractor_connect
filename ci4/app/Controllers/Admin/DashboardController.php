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
            'rows' => []
        ];

        switch ($type) {

            // Users Tables
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

            // Contractor Tables
            case 'contractors':

                $userModel = new \App\Models\UserModel();

                $contractors = $userModel->where('role_id', 3)->findAll();

                $data['headers'] = ['ID', 'Username', 'Email', 'Status'];
                foreach ($contractors as $contractor) {
                    $data['rows'][] = [
                        $contractor['id'],
                        esc($contractor['username']),
                        esc($contractor['email']),
                        ($contractor['is_active'] == 1) ? 'Active' : 'Inactive'
                    ];
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
        }

        return view('components/dashboard-table', $data);
    }

    public function settings() {
        return "Admin settings page";
    }
}