<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{

    // Create an account
    public function register()
    {
        // Input validation
        $rules = [
            'username' => 'required|min_length[3]|is_unique[users.username]',
            'password' => 'required|min_length[8]',
            'confirm_password' => 'matches[password]',
            'role_id'  => 'required|in_list[2,3]', // homeowners / contractors
        ];

        // Check input validation
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Attempt to create new account
        try{
            $userModel = new UserModel();

            $data = [
                'username'      => $this->request->getPost('username'),
                'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'role_id'       => $this->request->getPost('role_id'),
                'is_active'     => 1,
            ];

            // Handle failed save / catch model level errors
            if (!$userModel->insert($data)) {
                return redirect()->back()->withInput()->with('error', 'Database failed to save account.');
            }
            // Proceed to save
            return redirect()->to('/login')->with('success', 'Account created. Please login.');
        }
        // Catch database failure
        catch(\Exception $e){
            log_message('error', 'Registration error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong.');
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
