<?php

namespace Tests\Feature\Contractor;

use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\CIUnitTestCase;
use App\Models\UserModel;
use App\Models\ProjectModel;

class BidsControllerTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $refresh   = true;
    protected $namespace = 'App';

    // Helper function
    private function createContractor(array $overrides = []): int
    {
        $model = model(UserModel::class);
        return $model->insert(array_merge([
            'username'      => 'contractor_' . uniqid(),
            'email'         => uniqid() . '@example.com',
            'first_name'    => 'Contractor',
            'last_name'     => 'User',
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT),
            'role_id'       => 3,
            'is_active'     => 1
        ], $overrides));
    }

    private function createProject(array $overrides = []): int
    {
        $model = model(ProjectModel::class);
        return $model->insert(array_merge([
            'home_owner_id' => 1,
            'category_id'   => 1,
            'title'         => 'Test Project',
            'description'   => 'Description',
            'address'       => '123 Street',
            'status'        => 'bidding_open'
        ], $overrides));
    }

    // Tests

    public function testIndexShowsOnlyContractorsBids()
    {
        $contractorId = $this->createContractor(['username' => 'my_account']);
        $projectId    = $this->createProject(['title' => 'Fix Roof']);

        $this->db->table('bids')->insert([
            'project_id'    => $projectId,
            'contractor_id' => $contractorId,
            'bid_amount'    => 100.00,
        ]);

        $result = $this->withSession([
            'logged_in' => true,
            'user_id'   => $contractorId,
            'role_id'   => 3
        ])->get('contractor/bids');

        $result->assertStatus(200);
        $result->assertSee('Fix Roof');
    }

    // Create
    public function testCreateShowsValidBidForm(){
        $projectId    = $this->createProject(['title' => 'Fix Leaky Faucet']);
        $contractorId = $this->createContractor();

        $result = $this->withSession(['logged_in' => true, 'user_id' => $contractorId, 'role_id' => 3])
            ->get("contractor/bids/create/$projectId");

        $result->assertStatus(200);
        $result->assertSee('Fix Leaky Faucet');
    }
    public function testCreateRedirectsWhenProjectDoesNotExist()
    {
        $contractorId = $this->createContractor();

        $result = $this->withSession(['logged_in' => true, 'user_id' => $contractorId, 'role_id' => 3])
            ->get("contractor/bids/create/9999");

        $result->assertRedirectTo(site_url('contractor/browse'));
        $result->assertSessionHas('error', 'Project not found');
    }

    // Store
    public function testStoreSuccessfullyInsertsBid()
    {
        $contractorId = $this->createContractor();
        $projectId    = $this->createProject();

        $postData = [
            'bid_amount' => 1250.50,
            'details'    => 'I have 10 years of experience with this type of work.'
        ];

        $result = $this->withSession(['logged_in' => true, 'user_id' => $contractorId, 'role_id' => 3])
            ->post("contractor/bids/store/$projectId", $postData);

        $result->assertRedirectTo(site_url('contractor/bids'));
        $result->assertSessionHas('success', 'Bid submitted successfully.');

        // Manual check
        $count = $this->db->table('bids')->where([
            'project_id'    => $projectId,
            'contractor_id' => $contractorId,
            'bid_amount'    => 1250.50
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
            'details'       => 'Original bid',
            'total_cost'    => 500.00,
        ]);

        $result = $this->withSession([
            'logged_in' => true,
            'user_id'   => $contractorId,
            'role_id'   => 3
        ])->post("contractor/bids/store/$projectId", [
            'bid_amount' => 1000.00,
            'details'    => 'Trying to bid again'
        ]);

        $result->assertRedirectTo(site_url('contractor/browse/' . $projectId));
        $result->assertSessionHas('error', 'You already placed a bid for this project.');
    }
}
