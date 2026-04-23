<?php

namespace Tests\Feature\Contractor;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use App\Models\UserModel;
use App\Models\ProjectModel;
use Faker\Factory;

class BrowseControllerTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $namespace = 'App';
    protected $refresh   = true;
    private $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
    }

    // Helper methods
    private function createContractor(): int
    {
        $model = model(UserModel::class);
        return $model->insert([
            'username'      => $this->faker->userName,
            'email'         => $this->faker->safeEmail,
            'first_name'    => $this->faker->firstName,
            'last_name'     => $this->faker->lastName,
            'phone'         => $this->faker->phoneNumber,
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT),
            'role_id'       => 2,
            'is_active'     => 1
        ]);
    }

    private function createProject(array $overrides = []): int
    {
        $this->db->table('categories')->insert(['name' => $this->faker->word]);
        $catId = $this->db->insertID();

        $this->db->table('users')->insert([
            'username'   => $this->faker->userName,
            'email'      => $this->faker->safeEmail,
            'first_name' => $this->faker->firstName,
            'last_name'  => $this->faker->lastName,
            'phone'      => $this->faker->phoneNumber,
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT),
            'role_id'    => 3,
            'is_active'  => 1
        ]);
        $ownerId = $this->db->insertID();

        $data = array_merge([
            'home_owner_id' => $ownerId,
            'category_id'   => $catId,
            'title'         => $this->faker->sentence(3),
            'description'   => $this->faker->paragraph,
            'address'       => $this->faker->address,
            'status'        => 'bidding_open'
        ], $overrides);

        $this->db->table('projects')->insert($data);
        return $this->db->insertID();
    }

    public function testIndexShowsOnlyAvailableProjects()
    {
        $contractorId = $this->createContractor();

        $availableTitle = 'Available ' . $this->faker->word;
        $closedTitle    = 'Closed ' . $this->faker->word;
        $alreadyBidTitle = 'Already Bid ' . $this->faker->word;

        $this->createProject(['title' => $availableTitle, 'status' => 'bidding_open']);
        $this->createProject(['title' => $closedTitle, 'status' => 'closed']);

        $alreadyBidId = $this->createProject([
            'title'  => $alreadyBidTitle,
            'status' => 'bidding_open'
        ]);

        $this->db->table('bids')->insert([
            'project_id'    => $alreadyBidId,
            'contractor_id' => $contractorId,
            'bid_amount'    => 100,
            'total_cost'    => 100,
            'status'    => 'submitted',
        ]);

        $result = $this->withSession([
            'logged_in' => true,
            'user_id'   => $contractorId,
            'role_id'   => 2,
        ])->get('contractor/browse');

        $result->assertStatus(200);
        $result->assertSee($availableTitle);
        $result->assertDontSee($closedTitle);
        $result->assertDontSee($alreadyBidTitle);
    }

    public function testDetailsMethodCoverageDirect()
    {
        $contractorId = $this->createContractor();
        $projectTitle = 'Direct Test ' . $this->faker->word;
        $projectId    = $this->createProject(['title' => $projectTitle]);

        // Attempt request
        $result = $this->withSession([
            'user_id'   => $contractorId,
            'logged_in' => true,
            'role_id'   => 2,
        ])->get("contractor/browse/$projectId");


        // Assertions
        $result->assertStatus(200);
        $result->assertSee($projectTitle);
    }

    public function testDetailsRedirectsForInvalidProject()
    {
        $contractorId = $this->createContractor();

        $result = $this->withSession([
            'user_id'   => $contractorId,
            'logged_in' => true,
            'role_id'   => 2,
        ])->get("contractor/browse/9999");

        $result->assertRedirectTo(site_url('contractor/browse'));
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Project not found', session('error'));
    }
}