<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use App\Models\UserModel;
use Faker\Factory;

class UserAuthTest extends CIUnitTestCase {
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $refresh   = true;
    protected $namespace = 'App';
    private $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
    }

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
        $email = $this->faker->safeEmail;
        $password = 'Password123';

        $model = model(UserModel::class);
        $model->insert([
            'username'      => $this->faker->userName,
            'email'         => $email,
            'first_name'    => $this->faker->firstName,
            'last_name'     => $this->faker->lastName,
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
        $email = $this->faker->safeEmail;
        $password = 'Password123';

        $model = model(UserModel::class);
        $model->insert([
            'username'      => $this->faker->userName,
            'email'         => $email,
            'first_name'    => $this->faker->firstName,
            'last_name'     => $this->faker->lastName,
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
        $email = $this->faker->safeEmail;
        $model = model(UserModel::class);

        $model->insert([
            'username'      => $this->faker->userName,
            'email'         => $email,
            'first_name'    => $this->faker->firstName,
            'last_name'     => $this->faker->lastName,
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
            'email'    => $this->faker->unique()->safeEmail,
            'password' => 'somepassword'
        ]);

        $result->assertRedirectTo(site_url('login'));
        $result->assertSessionHas('error', 'User not found.');
    }

    public function testLoginFailsInactiveAccount()
    {
        $email = $this->faker->safeEmail;
        $password = 'password123';
        $model = model(UserModel::class);
        $model->insert([
            'username'      => $this->faker->userName,
            'email'         => $email,
            'first_name'    => $this->faker->firstName,
            'last_name'     => $this->faker->lastName,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'role_id'       => 2,
            'is_active'     => 0,
        ]);

        $result = $this->post('login', [
            'email'    => $email,
            'password' => $password
        ]);

        $result->assertRedirectTo(site_url('login'));
        $result->assertSessionHas('error', 'Account is inactive.');
    }

    public function testRegisterSuccess()
    {
        $result = $this->post('register', [
            'email'            => $this->faker->safeEmail,
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
            'email'            => $this->faker->safeEmail,
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
            'email'            => $this->faker->safeEmail,
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
            'email'            => $this->faker->safeEmail,
            'password'         => 'Password123!',
            'confirm_password' => 'Password123!',
            'role_id'          => 'not-an-integer-forcing-error'
        ]);

        $result->assertRedirect();
        $result->assertSessionHas('error');
    }
}