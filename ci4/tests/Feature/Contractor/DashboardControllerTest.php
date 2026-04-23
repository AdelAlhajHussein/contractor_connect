<?php

namespace Tests\Feature\Contractor;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use App\Models\UserModel;
use Faker\Factory;

class DashboardControllerTest extends CIUnitTestCase {
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

    public function testIndexShowsContractorDashboard()
    {
        $db = \Config\Database::connect();

        $this->db->table('users')->insert([
            'username'      => $this->faker->userName,
            'first_name'    => $this->faker->firstName,
            'last_name'     => $this->faker->lastName,
            'email'         => $this->faker->safeEmail,
            'phone'         => $this->faker->phoneNumber,
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT),
            'role_id'       => 2,
            'is_active'     => 1
        ]);
        $contractorId = $db->insertID();

        $result = $this->withSession([
            'user_id'   => $contractorId,
            'logged_in' => true,
            'role_id'   => 2
        ])->get('contractor/dashboard');

        $result->assertStatus(200);

        // Assertions
        $result->assertSee('Contractor Dashboard');
        $result->assertSee('Projects');
        $result->assertSee('Bids');
        $result->assertSee('Browse');
        $result->assertSee('Profile');
    }
}