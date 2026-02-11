<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;


class ContractorsController extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();

        $q      = trim($this->request->getGet('q') ?? '');
        $status = $this->request->getGet('status');

        $builder = $userModel
            ->select('users.id, users.username, users.first_name, users.last_name, users.email, users.phone, users.is_active, users.created_at,
                  contractor_profiles.address, contractor_profiles.city, contractor_profiles.province, contractor_profiles.postal_code')
            ->join('contractor_profiles', 'contractor_profiles.contractor_id = users.id', 'left')
            ->where('users.role_id', 2); // Contractors (based on your DB)

        // Search
        if ($q !== '') {
            $builder->groupStart()
                ->like('users.username', $q)
                ->orLike('users.email', $q)
                ->orLike('users.first_name', $q)
                ->orLike('users.last_name', $q)
                ->orLike('contractor_profiles.city', $q)
                ->groupEnd();
        }

        // Status filter
        if ($status !== null && $status !== '') {
            $builder->where('users.is_active', (int)$status);
        }

        $data['contractors'] = $builder->orderBy('users.id', 'DESC')->findAll();

        return view('admin/contractors/index', $data);
    }

    public function toggle($id)
    {
        $userModel = new UserModel();

        $user = $userModel->select('id, is_active')->find($id);
        if (!$user) {
            return redirect()->to(site_url('admin/contractors'));
        }

        $newStatus = ((int)$user['is_active'] === 1) ? 0 : 1;
        $userModel->update($id, ['is_active' => $newStatus]);

        return redirect()->to(site_url('admin/contractors'));
    }



}
