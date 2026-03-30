<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    public function registerForm()
    {
        return view('auth/register');
    }

    public function register()
    {
        $rules = [
            'username'          => 'required|min_length[3]|is_unique[users.username]',
            'password'          => 'required|min_length[8]',
            'confirm_password'  => 'required|matches[password]',
            'role_id'           => 'required|in_list[2,3]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $userModel = new UserModel();

            $data = [
                'username'      => trim((string) $this->request->getPost('username')),
                'password_hash' => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
                'role_id'       => (int) $this->request->getPost('role_id'),
                'is_active'     => 1,
            ];

            if (!$userModel->insert($data)) {
                return redirect()->back()->withInput()->with('error', 'Database failed to save account.');
            }

            return redirect()->to('/login')->with('success', 'Account created. Please login.');
        } catch (\Throwable $e) {
            log_message('error', 'Registration error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Something went wrong.');
        }
    }

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
            return redirect()->to('/login')->with('error', 'User not found.');
        }

        if ((int) $user['is_active'] !== 1) {
            return redirect()->to('/login')->with('error', 'Account is inactive.');
        }

        if (!password_verify($password, $user['password_hash'])) {
            return redirect()->to('/login')->with('error', 'Password incorrect.');
        }

        session()->regenerate(true);

        session()->set([
            'user_id'   => (int) $user['id'],
            'username'  => $user['username'],
            'role_id'   => (int) $user['role_id'],
            'logged_in' => true,
        ]);

        if ((int) $user['role_id'] === 1) {
            return redirect()->to('/admin/dashboard');
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
