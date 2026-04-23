<?php

namespace Tests\Feature\Homeowner;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use Config\Services;
use Faker\Factory;

class DashboardControllerTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $refresh = true;
    protected $namespace = 'App';
    private $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
    }

    public function testIndexShowsDashboard()
    {
        $db = \Config\Database::connect();

        // Use db to ensure the controller finds the user
        $this->db->table('users')->insert([
            'username'      => $this->faker->userName,
            'first_name'    => $this->faker->firstName,
            'last_name'     => $this->faker->lastName,
            'email'         => $this->faker->safeEmail,
            'phone'         => $this->faker->phoneNumber,
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT),
            'role_id'       => 3,
            'is_active'     => 1,
        ]);
        $homeownerId = $db->insertID();

        // Attempt the request
        $result = $this->withSession([
            'user_id'   => (int)$homeownerId,
            'logged_in' => true,
            'role_id'   => 3,
        ])->get('homeowner/dashboard');

        // Verify page loads
        $result->assertStatus(200);

        // Assert UI elements instead of the name
        $result->assertSee('Homeowner Dashboard');
        $result->assertSee('Browse');
        $result->assertSee('Projects');
        $result->assertSee('Bids');
    }
}