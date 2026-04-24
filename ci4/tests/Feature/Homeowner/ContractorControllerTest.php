<?php

namespace Tests\Feature\Homeowner;

use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\CIUnitTestCase;
use Faker\Factory;

final class ContractorControllerTest extends CIUnitTestCase
{
    use DatabaseTestTrait, FeatureTestTrait;

    protected $refresh   = true;
    protected $namespace = 'App';
    private $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
    }

    public function testViewReturnsSuccessAndCorrectContractorData()
    {
        $db = db_connect();

       // Create contractor user
        $firstName = $this->faker->firstName;
        $lastName  = $this->faker->lastName;

        $db->table('users')->insert([
            'email'         => $this->faker->safeEmail,
            'username'      => $this->faker->userName,
            'first_name'    => $firstName,
            'last_name'     => $lastName,
            'role_id'       => 2,
            'is_active'     => 1,
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT),
        ]);

        $contractorId = $db->insertID();

        // Simulate a homeowner session
        $result = $this->withSession([
            'logged_in' => true,
            'user_id'   => 99,
            'role_id'   => 3,
        ])->get("homeowner/contractor/view/$contractorId");

        // Assertions
        $result->assertStatus(200);

        // Verify the view sees the specific contractor created by Faker
        $result->assertSee($firstName);
        $result->assertSee($lastName);
    }

    public function testViewHandlesInvalidContractorId()
    {
        // Simulate session
        $result = $this->withSession([
            'logged_in' => true,
            'role_id'   => 3,
        ])->get("homeowner/contractor/view/994129"); // Non-existent ID

        $result->assertStatus(200);
    }
}