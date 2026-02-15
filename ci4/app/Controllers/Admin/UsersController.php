<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UsersController extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();

        $q      = trim($this->request->getGet('q') ?? '');
        $roleId = $this->request->getGet('role_id');
        $status = $this->request->getGet('status');

        $builder = $userModel->select('id, username, first_name, last_name, email, role_id, is_active, created_at');

        // Search
        if ($q !== '') {
            $builder->groupStart()
                ->like('username', $q)
                ->orLike('email', $q)
                ->orLike('first_name', $q)
                ->orLike('last_name', $q)
                ->groupEnd();
        }

        // Filter by role
        if ($roleId !== null && $roleId !== '') {
            $builder->where('role_id', (int) $roleId);
        }

        // Filter by status
        if ($status !== null && $status !== '') {
            $builder->where('is_active', (int) $status);
        }

        $data['users'] = $builder->orderBy('id', 'DESC')->findAll();

        return view('admin/users/index', $data);
    }

    public function toggle($id)
    {
        $userModel = new UserModel();

        $user = $userModel->select('id, is_active')->find($id);
        if (!$user) {
            return redirect()->to(site_url('admin/users'));
        }

        $newStatus = ((int)$user['is_active'] === 1) ? 0 : 1;
        $userModel->update($id, ['is_active' => $newStatus]);

        return redirect()->to(site_url('admin/users'));
    }

    public function updateRole($id)
    {
        $userModel = new UserModel();

        $newRoleId = (int) ($this->request->getPost('role_id') ?? 0);

        // Allow only valid roles: 1 Admin, 2 Homeowner, 3 Contractor
        if (!in_array($newRoleId, [1, 2, 3], true)) {
            return redirect()->to(site_url('admin/users'));
        }

        // Make sure user exists
        $user = $userModel->select('id, role_id')->find($id);
        if (!$user) {
            return redirect()->to(site_url('admin/users'));
        }

        // Update role
        $userModel->update($id, ['role_id' => $newRoleId]);

        return redirect()->to(site_url('admin/users'));
    }




}
