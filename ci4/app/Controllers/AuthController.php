<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    public function loginForm()
    {
        return view('auth/login');
    }

    public function login()
    {
        $username = trim((string) $this->request->getPost('username'));
        $password = (string) $this->request->getPost('password');

        if ($username === '' || $password === '') {
            return redirect()->to('/login')->with('error', 'Username and password are required.');
        }

        $userModel = new UserModel();

        $user = $userModel
            ->where('username', $username)
            ->where('deleted_at', null)
            ->first();

        if (!$user) {
            return redirect()->to('/login')->with('error', 'Invalid credentials.');
        }

        if ((int) $user['is_active'] !== 1) {
            return redirect()->to('/login')->with('error', 'Account is inactive.');
        }

        if (!password_verify($password, $user['password_hash'])) {
            return redirect()->to('/login')->with('error', 'Invalid credentials.');
        }

        session()->regenerate(true);

        session()->set([
            'user_id'   => (int) $user['id'],
            'username'  => $user['username'],
            'role_id'   => (int) $user['role_id'],
            'logged_in' => true
        ]);

        if ((int) $user['role_id'] === 1) {
            return redirect()->to('/admin/users');
        }

        if ((int) $user['role_id'] === 2) {
            return redirect()->to('/homeowner/dashboard');
        }

        if ((int) $user['role_id'] === 3) {
            return redirect()->to('/contractor/dashboard');
        }

        return redirect()->to('/');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
