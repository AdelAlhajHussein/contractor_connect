<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;


class HomeownersController extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();

        $q      = trim($this->request->getGet('q') ?? '');
        $status = $this->request->getGet('status');

        $builder = $userModel
            ->select('users.id, users.username, users.first_name, users.last_name, users.email, users.phone, users.is_active, users.created_at,
                  home_owner_profiles.address, home_owner_profiles.city, home_owner_profiles.province, home_owner_profiles.postal_code')
            ->join('home_owner_profiles', 'home_owner_profiles.home_owner_id = users.id', 'left')
            ->where('users.role_id', 3); // Homeowners (change if your DB uses a different role_id)

        // Search
        if ($q !== '') {
            $builder->groupStart()
                ->like('users.username', $q)
                ->orLike('users.email', $q)
                ->orLike('users.first_name', $q)
                ->orLike('users.last_name', $q)
                ->orLike('home_owner_profiles.city', $q)
                ->groupEnd();
        }


        if ($status !== null && $status !== '') {
            $builder->where('users.is_active', (int)$status);
        }

        $data['homeowners'] = $builder->orderBy('users.id', 'DESC')->findAll();

        return view('admin/homeowners/index', $data);
    }

    public function toggle($id)
    {
        $userModel = new UserModel();

        $user = $userModel->select('id, is_active')->find($id);
        if (!$user) {
            return redirect()->to(site_url('admin/homeowners'));
        }

        $newStatus = ((int)$user['is_active'] === 1) ? 0 : 1;
        $userModel->update($id, ['is_active' => $newStatus]);

        return redirect()->to(site_url('admin/homeowners'));
    }


}
