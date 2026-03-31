<?php

namespace Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;
use App\Models\UserModel;
use Faker\Factory as FakerFactory;

class UserAuthTest extends CIUnitTestCase {
    use FeatureTestTrait, DatabaseTestTrait;

    protected $refresh = true;
    protected $namespace = 'App';
    protected $faker;

    protected function setUp(): void{
        parent::setUp();
        $this->faker = FakerFactory::create();
    }

    /**
     * Scenario: Successful user login
     * Expect:
     * - User credentials are validated from the DB
     * - isLoggedIn is set to true
     * - User is redirected to the dashboard
     */
    public function testLoginSuccessRedirectsToDashboard()
    {
        $username = 'test_user';
        $password = 'Password123';

        $db = \Config\Database::connect();
        $db->table('users')->where('username', $username)->delete();

        $db->table('users')->insert([
            'username' => $username,
            'email' => 'admin@test.com',
            'first_name' => 'Admin',
            'last_name' => 'User',
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'role_id' => 1,
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // Simulate successful login
        $result = $this->post('login', [
            'username' => $username,
            'password' => $password,
        ]);

        ////Verification
        // HTTP response is a 302 redirect
        $result->assertStatus(302);

        // Admin is redirected to the Admin Dashboard
        $result->assertRedirectTo(site_url('admin/dashboard'));

        // Session contains the authentication flag
        $result->assertSessionHas('logged_in', true);
    }

    /**
     * Scenario: Password is incorrect
     * Expect:
     * - Authentication fails
     * - The request redirected to the user login page
     * -
     */
    public function testLoginFailsWithWrongPassword()
    {
        $username = 'test_user';
        $email = 'existing@example.com';
        $userModel = new UserModel();
        $userModel->insert([
            'username'      => $username,
            'email'         => $email,
            'password_hash' => password_hash('correct_pass',PASSWORD_DEFAULT),
            'role_id'       => 1,
            'is_active'     => 1,
        ]);

        // Simulate login with incorrect password
        $result = $this->post('login', [
            'login_email' => $username,
            'password'   => 'wrong_password',
        ]);

        //// Verification
        // Redirect to login
        $result->assertRedirectTo(site_url('login'));

        // Redirect found
        $result->assertStatus(302);

        // isLoggedIn is still false
        $result->assertSessionMissing('isLoggedIn');

        // An error message is displayed to the user
        $result->assertSessionHas('error');
    }

    /**
     * Scenario: Login with invalid email
     * Expect:
     * - Authentication fails
     * - The request redirected to the user login page
     * - An error message is displayed to the user
     */
    public function testLoginFailsWithWrongUsername()
    {
        // Simulate login with invalid email
        $result = $this->post('login', [
            'username' => 'username_not_in_database',
            'password'    => 'random_password'
        ]);

        //// Verification
        // Request redirected to the login page
        $result->assertRedirectTo(site_url('login'));

        // Error message displayed
        $result->assertSessionHas('error', 'User not found.');
    }

    /**
     * Scenario: Accessing admin URL without authentication
     * Expect:
     * - The Auth filter detects that there isn't a Session
     * - The request redirected to the user login page
     */
    public function testGuestCannotAccessAdmin()
    {
        $result = $this->get('admin/settings');

        //// Verification
        $result->assertRedirectTo(site_url('login'));
    }


}