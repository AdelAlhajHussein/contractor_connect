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
        $email = 'user@email.com';
        $password = 'Password123';

        $userModel = new UserModel();
        $userModel->insert([
            'username' => 'contractor_bob',
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'role_id' => 1,
            'is_active' => 1
        ]);

        // Simulate successful login
        $result = $this->post('login', [
            'login_email' => $email,
            'password'    => $password,
        ]);

        ////Verification
        // HTTP response is a 302 redirect
        $result->assertStatus(302);

        // User is redirected to the dashboard
        $result->assertRedirectTo('dashboard');

        // Session contains the authentication flag
        $result->assertSessionHas('isLoggedIn', true);
        $result->assertSessionHas('user_email', $email);
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
        $email = 'existing@example.com';
        $userModel = new UserModel();
        $userModel->insert([
            'username'      => 'unique_name',
            'email'         => $email,
            'password_hash' => password_hash('correct_pass',PASSWORD_DEFAULT),
            'role_id'       => 1,
        ]);

        // Simulate login with incorrect password
        $result = $this->post('login', [
            'login_email' => $email,
            'password'   => 'wrong_password',
        ]);

        //// Verification
        // Redirect to login
        $result->assertRedirectTo('login');

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
    public function testLoginFailsWithWrongEmail()
    {
        // Simulate login with invalid email
        $result = $this->post('login', [
            'login_email' => 'email_not_in_database@example.com',
            'password'    => 'random_password'
        ]);

        //// Verification
        // Request redirected to the login page
        $result->assertRedirectTo('login');

        // Error message displayed
        $result->assertSessionHas('error');
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
        $result->assertRedirectTo('login');
    }


}