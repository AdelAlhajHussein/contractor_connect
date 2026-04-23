<?php

namespace Tests\Feature\Homeowner;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use Faker\Factory;

class BidsControllerTest extends CIUnitTestCase
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

    public function testIndexShowsHomeownerBids()
    {
        $db = \Config\Database::connect();

        // Use Faker for homeowner data
        $homeownerUsername = $this->faker->userName;
        $homeownerEmail = $this->faker->safeEmail;

        $this->db->table('users')->insert([
            'username'      => $homeownerUsername,
            'first_name'    => $this->faker->firstName,
            'last_name'     => $this->faker->lastName,
            'email'         => $homeownerEmail,
            'phone'         => $this->faker->phoneNumber,
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT),
            'role_id'       => 3,
            'is_active'     => 1,
        ]);
        $homeownerId = $db->insertID();

        // Use Faker for contractor data
        $this->db->table('users')->insert([
            'username'      => $this->faker->userName,
            'first_name'    => $this->faker->firstName,
            'last_name'     => $this->faker->lastName,
            'email'         => $this->faker->safeEmail,
            'phone'         => $this->faker->phoneNumber,
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT),
            'role_id'       => 2,
            'is_active'     => 1,
        ]);
        $contractorId = $db->insertID();

        $this->db->table('categories')->insert(['name' => $this->faker->word]);
        $catId = $db->insertID();

        $projectTitle = 'Fix ' . $this->faker->word;
        $this->db->table('projects')->insert([
            'home_owner_id' => $homeownerId,
            'category_id'   => $catId,
            'title'         => $projectTitle,
            'description'   => $this->faker->sentence,
            'address'       => $this->faker->address,
            'status'        => 'bidding_open',
        ]);
        $projectId = $db->insertID();

        $bidAmount = 500.00;
        $this->db->table('bids')->insert([
            'project_id'   => $projectId,
            'contractor_id'=> $contractorId,
            'bid_amount'   => $bidAmount,
            'total_cost'   => $bidAmount,
            'status'       => 'submitted',
            'created_at'   => date('Y-m-d H:i:s'),
        ]);

        $result = $this->withSession([
            'user_id'   => (int)$homeownerId,
            'logged_in' => true,
            'role_id'   => 3
        ])->get('homeowner/bids');

        // Assertions
        $result->assertStatus(200);
        $result->assertSee($projectTitle);
        $result->assertSee(number_format($bidAmount, 2));
    }
}