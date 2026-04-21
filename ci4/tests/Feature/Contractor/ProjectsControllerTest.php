<?php

namespace Tests\Feature\Contractor;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use App\Models\UserModel;

class ProjectsControllerTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $refresh   = true;
    protected $namespace = 'App';


    public function testIndexShowsContractorProjects()
    {
        $userModel = model(UserModel::class);

        // Create a homeowner user
        $homeOwnerId = $userModel->insert([
            'username'   => 'homeowner_test',
            'email'      => 'home@test.com',
            'first_name' => 'Jane',
            'last_name'  => 'Doe',
            'role_id'    => 2,
            'is_active'  => 1,
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT)
        ]);

        // Create a category
        $this->db->table('categories')->insert([
            'name' => 'Roofing'
        ]);
        $categoryId = $this->db->insertID();

        // Create a contractor user
        $contractorId = $userModel->insert([
            'username'   => 'contractor_pro',
            'email'      => 'pro@test.com',
            'first_name' => 'John',
            'last_name'  => 'Contractor',
            'role_id'    => 3,
            'is_active'  => 1,
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT)
        ]);

        // Create a project
        $this->db->table('projects')->insert([
            'home_owner_id' => $homeOwnerId,
            'category_id'   => $categoryId,
            'title'         => 'Fix My Roof',
            'description'   => 'Leaking roof needs repair',
            'address'       => '123 Test St',
            'budget_min'    => 500.00,
            'budget_max'    => 1000.00,
            'status'        => 'open',
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);
        $projectId = $this->db->insertID();

        // Link contractor to a bid
        $this->db->table('bids')->insert([
            'project_id'    => $projectId,
            'contractor_id' => $contractorId,
            'bid_amount'    => 500.00,
            'status'        => 'pending',
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);

        // Attempt the request
        $result = $this->withSession([
            'user_id'   => (int)$contractorId,
            'logged_in' => true,
            'role_id'   => 3
        ])->get('contractor/projects');

        // Assertions
        $result->assertStatus(200);
        $result->assertSee('Fix My Roof');
        $result->assertSee('500.00');
    }
}