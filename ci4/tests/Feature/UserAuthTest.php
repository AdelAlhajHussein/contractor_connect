<?php

namespace Feature;

use App\Models\UserModel;
use Faker\Factory as FakerFactory;
use Tests\Support\ProjectTestCase;

class UserAuthTest extends ProjectTestCase {

    protected $faker;

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
        $email = "test_user@mail.com";

        $this->db->table('users')->insert([
            'username' => $username,
            'email' => $email,
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
        $result->assertRedirectTo(site_url('admin/dashboard'));

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
        $email = "test_user@mail.com";

        $userModel = new UserModel();
        $userModel->insert([
            'username'      => $username,
            'email'         => 'existing@example.com',
            'password_hash' => password_hash('correct_pass', PASSWORD_DEFAULT),
            'role_id'       => 1,
            'is_active'     => 1,
        ]);

        // Simulate login with incorrect password
        $result = $this->post('login', [
            'username' => $username,
            'password'    => 'wrong_password',
        ]);

        //// Verification
        // Redirect to login
        $result->assertRedirectTo(site_url('login'));

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
        $email = 'test_user@mail.com';

        // Simulate login with invalid email
        $result = $this->post('login', [
            'email' => $email,
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