<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;
use App\Models\UserModel;

class UserAuthTest extends CIUnitTestCase
{
    use FeatureTestTrait, DatabaseTestTrait;

    protected $refresh = true;

    protected $namespace = 'App';

    /**
     * Scenario: Successful user login
     * Expect:
     * - User credentials are validated from the DB
     * - isLoggedIn is set to true
     * - User is redirected to the dashboard
     */
    public function testLoginSuccessRedirectsToDashboard()
    {
        $email = 'user@email.com';
        $plainPassword = 'Password123';

        $userModel = new UserModel();
        $userModel->insert([
            'username'   => 'contractor_bob',
            'email'      => $email,
            'password'   => password_hash($plainPassword, PASSWORD_DEFAULT),
            'role_id'    => 1,
            'is_active'  => 1
        ]);

        // Simulate login
        $result = $this->post('login', [
            'login_email' => $email,
            'password'    => $plainPassword
        ]);

        // ------ Verification -------
        // HTTP response is a 302 redirect
        $result->assertStatus(302);

        // User is redirected to the dashboard
        $result->assertRedirectTo('dashboard');

        // Session contains the authentication flag
        $result->assertSessionHas('isLoggedIn', true);
        $result->assertSessionHas('user_email', $email);
    }

}