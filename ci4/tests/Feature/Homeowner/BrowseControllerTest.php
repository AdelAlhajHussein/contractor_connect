<?php

namespace Tests\Feature\Homeowner;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use Config\Services;
use Faker\Factory;

class BrowseControllerTest extends CIUnitTestCase
{
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

    public function testIndexShowsContractorsWithFilters()
    {
        $db = \Config\Database::connect();

        // Use Faker for contractor names and contact info
        $firstName = $this->faker->firstName;
        $lastName  = $this->faker->lastName;
        $city      = $this->faker->city;
        $province  = 'ON';

        // Create contractor
        $this->db->table('users')->insert([
            'username'      => $this->faker->userName,
            'first_name'    => $firstName,
            'last_name'     => $lastName,
            'email'         => $this->faker->safeEmail,
            'phone'         => $this->faker->phoneNumber,
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT),
            'role_id'       => 2,
            'is_active'     => 1
        ]);
        $contractorId = $db->insertID();

        // Create profile using the generated city
        $this->db->table('contractor_profiles')->insert([
            'contractor_id'   => $contractorId,
            'city'            => $city,
            'province'        => $province,
            'approval_status' => 'approved',
        ]);

        // Attempt to browse by city and province
        $result = $this->withSession([
            'user_id'   => 123,
            'logged_in' => true,
            'role_id'   => 3,
        ])->get("homeowner/browse?city=" . urlencode($city) . "&province=$province");

        // Assertions
        $result->assertStatus(200);
        $result->assertSee($firstName);
        $result->assertSee($lastName);
        $result->assertSee($city);
        $result->assertSee($province);
    }
}