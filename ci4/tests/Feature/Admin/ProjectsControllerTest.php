<?php

namespace Tests\Feature\Admin;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

class ProjectsControllerTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $refresh   = true;
    protected $namespace = 'App';

    // Helper function
    private function createProject(array $projectOverrides = [])
    {
        // Create a category
        $categoryId = $this->db->table('categories')->insert([
            'name' => 'Renovation'
        ]);

        // Create a homeowner user
        $homeOwnerId = $this->db->table('users')->insert([
            'username'      => 'owner_' . uniqid(),
            'email'         => uniqid() . '@test.com',
            'first_name'    => 'Project',
            'last_name'     => 'Owner',
            'role_id'       => 2,
            'is_active'     => 1,
            'password_hash' => 'hash'
        ]);

        // Create a project
        $projectId = $this->db->table('projects')->insert(array_merge([
            'title'         => 'Test Project',
            'category_id'   => $categoryId,
            'home_owner_id' => $homeOwnerId,
            'status'        => 'bidding_open',
            'budget_min'    => 1000,
            'budget_max'    => 5000,
            'deadline_date' => date('Y-m-d', strtotime('+1 month')),
            'description'   => 'Test description'
        ], $projectOverrides));

        return $this->db->insertID();
    }

    // Tests
    public function testIndexFiltersAndSearch()
    {
        $this->createProject([
            'title' => 'Fix the Roof',
            'status' => 'open'
        ]);
        $this->createProject([
            'title' => 'Paint the Wall',
            'status' => 'completed'
        ]);

        $session = ['user_id' => 999, 'logged_in' => true, 'role_id' => 1];

        // Attempt filter by title
        $resSearch = $this->withSession($session)->get('admin/projects?q=Roof');
        $resSearch->assertStatus(200);
        $resSearch->assertSee('Fix the Roof');
        $resSearch->assertDontSee('Paint the Wall');

        // Attempt filter by status
        $resStatus = $this->withSession($session)->get('admin/projects?status=completed');
        $resStatus->assertSee('Paint the Wall');
        $resStatus->assertDontSee('Fix the Roof');
    }

    public function testViewMethodLoadsDetails()
    {
        $projectId = $this->createProject(['title' => 'Specific Project']);
        $session = ['user_id' => 999, 'logged_in' => true, 'role_id' => 1];

        // Attempt to view details
        $result = $this->withSession($session)->get("admin/projects/view/$projectId");
        $result->assertStatus(200);
        $result->assertSee('Specific Project');
        $result->assertSee('Renovation');

        // Attempt to view invalid id
        $result404 = $this->withSession($session)->get("admin/projects/view/99999");
        $result404->assertRedirectTo(site_url('admin/projects'));
    }

    public function testCancelMethodTransitionsStatus()
    {
        $projectId = $this->createProject(['status' => 'open']);
        $session = ['user_id' => 999, 'logged_in' => true, 'role_id' => 1];

        // Attempt to cancel project
        $result = $this->withSession($session)->get("admin/projects/cancel/$projectId");
        $result->assertRedirectTo(site_url('admin/projects'));
        $this->seeInDatabase('projects', ['id' => $projectId, 'status' => 'cancelled']);

        // Attempt to cancel a completed project
        $compProjectId = $this->createProject(['status' => 'completed']);
        $this->withSession($session)->get("admin/projects/cancel/$compProjectId");
        $this->seeInDatabase('projects', ['id' => $compProjectId, 'status' => 'completed']);
    }

    public function testCloseBiddingTransitionsStatus()
    {
        $projectId = $this->createProject(['status' => 'bidding_open']);
        $session = ['user_id' => 999, 'logged_in' => true, 'role_id' => 1];

        // Attempt to close bidding
        $result = $this->withSession($session)->get("admin/projects/closeBidding/$projectId");
        $result->assertRedirectTo(site_url('admin/projects'));
        $this->seeInDatabase('projects', ['id' => $projectId, 'status' => 'in_progress']);

        // Verify only projects with open bidding can have bidding closed
        $otherProjectId = $this->createProject(['status' => 'open']);
        $this->withSession($session)->get("admin/projects/closeBidding/$otherProjectId");
        $this->seeInDatabase('projects', ['id' => $otherProjectId, 'status' => 'open']);
    }
}