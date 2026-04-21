<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use App\Models\UserModel;

class UserAuthTest extends CIUnitTestCase {
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $refresh   = true;
    protected $namespace = 'App';

    public function testRegisterFormLoads()
    {
        $result = $this->get('register');
        $result->assertStatus(200);
    }

    public function testLoginFormLoads()
    {
        $result = $this->get('login');
        $result->assertStatus(200);
    }

    public function testLoginRedirectsToHomeownerDashboard()
    {
        $email = 'homeowner@example.com';
        $password = 'Password123';

        $model = model(UserModel::class);
        $model->insert([
            'username'      => 'homeowner_user',
            'email'         => $email,
            'first_name'    => 'Home',
            'last_name'     => 'Owner',
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'role_id'       => 2,
            'is_active'     => 1,
        ]);

        $result = $this->post('login', [
            'email'    => $email,
            'password' => $password,
        ]);

        $result->assertRedirectTo(site_url('homeowner/dashboard'));
    }

    public function testLoginRedirectsToContractorDashboard()
    {
        $email = 'contractor@example.com';
        $password = 'Password123';

        $model = model(UserModel::class);
        $model->insert([
            'username'      => 'contractor_user',
            'email'         => $email,
            'first_name'    => 'Contract',
            'last_name'     => 'Or',
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'role_id'       => 3,
            'is_active'     => 1,
        ]);

        $result = $this->post('login', [
            'email'    => $email,
            'password' => $password,
        ]);

        $result->assertRedirectTo(site_url('contractor/dashboard'));
    }

    public function testLoginFailsWithWrongPassword()
    {
        $email = 'existing@example.com';
        $model = model(UserModel::class);

        $model->insert([
            'username'      => 'test_user',
            'email'         => $email,
            'first_name'    => 'Test',
            'last_name'     => 'User',
            'password_hash' => password_hash('correct_pass', PASSWORD_DEFAULT),
            'role_id'       => 1,
            'is_active'     => 1,
        ]);

        $result = $this->post('login', [
            'email'    => $email,
            'password' => 'wrong_password',
        ]);

        $result->assertRedirectTo(site_url('login'));
        $result->assertSessionHas('error', 'Password incorrect.');
    }

    public function testLoginFailsEmptyFields()
    {
        $result = $this->post('login', [
            'email'    => '',
            'password' => ''
        ]);

        $result->assertRedirectTo(site_url('login'));
        $result->assertSessionHas('error', 'Email and password are required.');
    }

    public function testLoginFailsUserNotFound()
    {
        $result = $this->post('login', [
            'email'    => 'nonexistent@example.com',
            'password' => 'somepassword'
        ]);

        $result->assertRedirectTo(site_url('login'));
        $result->assertSessionHas('error', 'User not found.');
    }

    public function testLoginFailsInactiveAccount()
    {
        $email = 'inactive@example.com';
        $model = model(UserModel::class);
        $model->insert([
            'username'      => 'inactive_user',
            'email'         => $email,
            'first_name'    => 'Inactive',
            'last_name'     => 'User',
            'password_hash' => password_hash('password123', PASSWORD_DEFAULT),
            'role_id'       => 2,
            'is_active'     => 0,
        ]);

        $result = $this->post('login', [
            'email'    => $email,
            'password' => 'password123'
        ]);

        $result->assertRedirectTo(site_url('login'));
        $result->assertSessionHas('error', 'Account is inactive.');
    }

    public function testRegisterSuccess()
    {
        $result = $this->post('register', [
            'email'            => 'new_success@example.com',
            'password'         => 'Password123!',
            'confirm_password' => 'Password123!',
            'role_id'          => 2
        ]);

        $result->assertRedirectTo(site_url('login'));
        $result->assertSessionHas('success', 'Account created. Please login.');
    }

    public function testRegisterFailsValidation()
    {
        $result = $this->post('register', [
            'email'            => '',
            'password'         => '123',
            'confirm_password' => '456'
        ]);

        $result->assertRedirect();
        $result->assertSessionHas('errors');
    }

    public function testRegisterSuccessRedirectsToLogin()
    {
        $result = $this->post('register', [
            'email'            => 'success_reg@example.com',
            'password'         => 'Password123!',
            'confirm_password' => 'Password123!',
            'role_id'          => 2
        ]);

        $result->assertRedirectTo(site_url('login'));
        $result->assertSessionHas('success', 'Account created. Please login.');
    }

    public function testLogoutRedirectsToLogin()
    {
        $result = $this->get('logout');
        $result->assertRedirectTo(site_url('login'));
    }

    public function testGuestCannotAccessAdmin()
    {
        $result = $this->get('admin/dashboard');
        $result->assertRedirectTo(site_url('login'));
    }

    public function testRegisterTriggersCatchBlock()
    {
        $result = $this->post('register', [
            'email'            => 'catch@example.com',
            'password'         => 'Password123!',
            'confirm_password' => 'Password123!',
            'role_id'          => 'not-an-integer'
        ]);

        $result->assertRedirect();
        $result->assertSessionHas('error');
    }

    public function testRegisterCatchBlockOnDatabaseError()
    {
        $result = $this->post('register', [
            'email'            => 'catch_me@example.com',
            'password'         => 'Password123!',
            'confirm_password' => 'Password123!',
            'role_id'          => 'not-an-integer-forcing-error'
        ]);

        $result->assertRedirect();
        $result->assertSessionHas('error');
    }
}