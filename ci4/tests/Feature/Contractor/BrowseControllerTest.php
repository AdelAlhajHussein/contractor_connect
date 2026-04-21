<?php

namespace Tests\Feature\Contractor;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use App\Models\UserModel;
use App\Models\ProjectModel;

class BrowseControllerTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $namespace = 'App';
    protected $refresh   = true;

    // Helper methods
    private function createContractor(): int
    {
        $model = model(UserModel::class);
        return $model->insert([
            'username'      => 'contractor_eric',
            'email'         => 'eric@contractor.com',
            'first_name'    => 'Eric',
            'last_name'     => 'L',
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT),
            'role_id'       => 3,
            'is_active'     => 1
        ]);
    }

    private function createProject(array $overrides = []): int
    {
        $model = model(ProjectModel::class);
        return $model->insert(array_merge([
            'home_owner_id' => 1,
            'category_id'   => 1,
            'title'         => 'Default Project',
            'description'   => 'Test description',
            'address'       => '123 Street',
            'status'        => 'bidding_open'
        ], $overrides));
    }

    public function testIndexShowsOnlyAvailableProjects()
    {
        $contractorId = $this->createContractor();

        $this->createProject(['title' => 'Available Project', 'status' => 'bidding_open']);
        $this->createProject(['title' => 'Closed Project', 'status' => 'closed']);

        $alreadyBidId = $this->createProject([
            'title'  => 'Already Bid',
            'status' => 'bidding_open'
        ]);

        $this->db->table('bids')->insert([
            'project_id'    => $alreadyBidId,
            'contractor_id' => $contractorId,
            'bid_amount'    => 100,
            'details' => 'Test bid'
        ]);

        $result = $this->withSession([
            'logged_in' => true,
            'user_id'   => $contractorId,
            'role_id'   => 3
        ])->get('contractor/browse');

        $result->assertStatus(200);
        $result->assertSee('Available Project');
        $result->assertDontSee('Closed Project');
        $result->assertDontSee('Already Bid');
    }

    public function testDetailsMethodCoverageDirect()
    {
        $contractorId = $this->createContractor();
        $projectId    = $this->createProject(['title' => 'Direct Test']);

        // Attempt request
        $result = $this->withSession([
            'user_id'   => $contractorId,
            'logged_in' => true,
            'role_id'   => 3
        ])->get("contractor/browse/$projectId");


        // Assertions
        $result->assertStatus(200);
        $result->assertSee('Direct Test');
    }

    public function testDetailsRedirectsForInvalidProject()
    {
        $contractorId = $this->createContractor();

        $result = $this->withSession([
            'user_id'   => $contractorId,
            'logged_in' => true,
            'role_id'   => 3
        ])->get("contractor/browse/9999");

        $result->assertRedirectTo(site_url('contractor/browse'));
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Project not found', session('error'));
    }
}