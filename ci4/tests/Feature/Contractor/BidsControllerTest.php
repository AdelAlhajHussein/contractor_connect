<?php

namespace Tests\Feature\Contractor;

use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\CIUnitTestCase;
use App\Models\UserModel;
use App\Models\ProjectModel;
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

    // Helper function
    private function createContractor(array $overrides = []): int
    {
        $model = model(UserModel::class);
        $userId =  $model->insert(array_merge([
            'username'      => $this->faker->userName,
            'email'         => $this->faker->safeEmail,
            'first_name'    => $this->faker->firstName,
            'last_name'     => $this->faker->lastName,
            'phone'         => $this->faker->phoneNumber,
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT),
            'role_id'       => 2,
            'is_active'     => 1
        ], $overrides));

        $this->db->table('contractor_profiles')->insert([
            'contractor_id'   => $userId,
            'city'            => $this->faker->city,
            'address'         => $this->faker->address,
            'approval_status' => 'approved'
        ]);

        return (int)$userId;
    }

    private function createProject(array $overrides = []): int
    {
        // Create category
        $this->db->table('categories')->insert(['name' => $this->faker->word]);
        $catId = $this->db->insertID();

        $this->db->table('users')->insert([
            'username'      => $this->faker->userName,
            'email'         => $this->faker->safeEmail,
            'first_name'    => $this->faker->firstName,
            'last_name'     => $this->faker->lastName,
            'phone'         => $this->faker->phoneNumber,
            'role_id'       => 3,
            'is_active'     => 1,
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT)
        ]);
        $homeOwnerId = $this->db->insertID();

        $projectData = array_merge([
            'home_owner_id' => $homeOwnerId,
            'category_id'   => $catId,
            'title'         => $this->faker->sentence(3),
            'description'   => $this->faker->paragraph,
            'address'       => $this->faker->address,
            'status'        => 'bidding_open'
        ], $overrides);

        $this->db->table('projects')->insert($projectData);
        return $this->db->insertID();
    }

    // Tests

    public function testIndexShowsOnlyContractorsBids()
    {
        $contractorId = $this->createContractor(['username' => 'my_account']);
        $projectTitle = 'Fix ' . $this->faker->word;
        $projectId    = $this->createProject(['title' => $projectTitle]);

        $this->db->table('bids')->insert([
            'project_id'        => $projectId,
            'contractor_id'     => $contractorId,
            'bid_amount'        => 100.00,
            'total_cost'        => 100.00,
            'status'            => 'submitted',
        ]);

        $result = $this->withSession([
            'logged_in' => true,
            'user_id'   => $contractorId,
            'role_id'   => 2,
        ])->get('contractor/bids');

        $result->assertStatus(200);
        $result->assertSee($projectTitle);
    }

    // Create
    public function testCreateShowsValidBidForm(){

        $projectTitle = 'Fix ' . $this->faker->word;
        $projectId    = $this->createProject([
            'title' => $projectTitle,
            'status'=> 'bidding_open',
        ]);
        $contractorId = $this->createContractor();

        $this->assertGreaterThan(0, $projectId);

        $result = $this->withSession([
            'logged_in' => true,
            'user_id'   => $contractorId,
            'role_id'   => 2
        ])->get("contractor/bids/create/$projectId");

        $result->assertStatus(200);
        $result->assertSee($projectTitle);
    }
    public function testCreateRedirectsWhenProjectDoesNotExist()
    {
        $contractorId = $this->createContractor();

        $result = $this->withSession(['logged_in' => true, 'user_id' => $contractorId, 'role_id' => 2])
            ->get("contractor/bids/create/9999");

        $result->assertRedirectTo(site_url('contractor/browse'));
        $result->assertSessionHas('error', 'Project not found');
    }

    // Store
    public function testStoreSuccessfullyInsertsBid()
    {
        $contractorId = $this->createContractor();
        $projectId    = $this->createProject();
        $bidAmount    = $this->faker->randomFloat(2, 100, 5000);

        $postData = [
            'bid_amount' => $bidAmount,
            'details'    => $this->faker->sentence(10)
        ];

        $result = $this->withSession(['logged_in' => true, 'user_id' => $contractorId, 'role_id' => 2])
            ->post("contractor/bids/store/$projectId", $postData);

        $result->assertRedirectTo(site_url('contractor/bids'));
        $result->assertSessionHas('success', 'Bid submitted successfully.');

        // Manual check
        $count = $this->db->table('bids')->where([
            'project_id'    => $projectId,
            'contractor_id' => $contractorId,
            'bid_amount'    => $bidAmount
        ])->countAllResults();

        $this->assertGreaterThan(0, $count);
    }
    public function testStorePreventsDuplicates(){

        $contractorId = $this->createContractor();
        $projectId    = $this->createProject();

        $this->db->table('bids')->insert([
            'project_id'    => $projectId,
            'contractor_id' => $contractorId,
            'bid_amount'    => 500.00,
            'status'        => 'submitted',
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s')
        ]);

        $result = $this->withSession([
            'logged_in' => true,
            'user_id'   => $contractorId,
            'role_id'   => 2,
        ])->post("contractor/bids/store/$projectId", [
            'bid_amount' => 1000.00,
            'details'    => 'Trying to bid again'
        ]);

        $result->assertRedirectTo(site_url('contractor/browse/' . $projectId));
        $result->assertSessionHas('error', 'You already placed a bid for this project.');
    }
}