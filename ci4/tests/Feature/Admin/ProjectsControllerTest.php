<?php

namespace Tests\Feature\Admin;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use Faker\Factory;

class ProjectsControllerTest extends CIUnitTestCase
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
    private function createProject(array $projectOverrides = [])
    {
        // Create a category
        $this->db->table('categories')->insert([
            'name' => $this->faker->word . ' Renovation'
        ]);
        $categoryId = $this->db->insertID();

        // Create a homeowner user
        $homeOwnerId = $this->db->table('users')->insert([
            'username'      => $this->faker->userName,
            'email'         => $this->faker->safeEmail,
            'first_name'    => $this->faker->firstName,
            'last_name'     => $this->faker->lastName,
            'is_active'     => 1,
            'role_id'       => 2,
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT),
        ]);

        // Create a project
        $this->db->table('projects')->insert(array_merge([
            'title'         => $this->faker->sentence(3),
            'category_id'   => $categoryId,
            'home_owner_id' => $homeOwnerId,
            'status'        => 'bidding_open',
            'budget_min'    => 1000,
            'budget_max'    => 5000,
            'deadline_date' => date('Y-m-d', strtotime('+1 month')),
            'description'   => $this->faker->paragraph,
            'address'       => $this->faker->address
        ], $projectOverrides));

        return $this->db->insertID();
    }

    // Tests
    public function testIndexFiltersAndSearch()
    {
        $roofTitle = 'Fix the ' . $this->faker->word . ' Roof';
        $wallTitle = 'Paint the ' . $this->faker->word . ' Wall';

        $this->createProject([
            'title' => $roofTitle,
            'status' => 'open'
        ]);
        $this->createProject([
            'title' => $wallTitle,
            'status' => 'completed'
        ]);

        $session = ['user_id' => 999, 'logged_in' => true, 'role_id' => 1];

        // Attempt filter by title
        $resSearch = $this->withSession($session)->get('admin/projects?q=Roof');
        $resSearch->assertStatus(200);
        $resSearch->assertSee($roofTitle);
        $resSearch->assertDontSee($wallTitle);

        // Attempt filter by status
        $resStatus = $this->withSession($session)->get('admin/projects?status=completed');
        $resStatus->assertSee($wallTitle);
        $resStatus->assertDontSee($roofTitle);
    }

    public function testViewMethodLoadsDetails()
    {
        $projectTitle = 'Specific Project ' . $this->faker->word;
        $projectId = $this->createProject(['title' => $projectTitle]);
        $session = ['user_id' => 999, 'logged_in' => true, 'role_id' => 1];

        // Attempt to view details
        $result = $this->withSession($session)->get("admin/projects/view/$projectId");
        $result->assertStatus(200);
        $result->assertSee($projectTitle);
        $result->assertSee('Renovation');

        // Attempt to view invalid id
        $result404 = $this->withSession($session)->get("admin/projects/view/99999");
        $result404->assertRedirectTo(site_url('admin/projects'));
    }

    public function testCancelMethodTransitionsStatus()
    {
        $projectId = $this->createProject(['status' => 'open']);
        $session = [
            'user_id' => 999,
            'logged_in' => true,
            'role_id' => 1
        ];

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
        $session = [
            'user_id' => 999,
            'logged_in' => true,
            'role_id' => 1,
            ];

        // Attempt to close bidding
        $result = $this->withSession($session)->get("admin/projects/close-bidding/$projectId");
        $result->assertRedirectTo(site_url('admin/projects'));
        $this->seeInDatabase('projects', ['id' => $projectId, 'status' => 'in_progress']);

        // Verify only projects with open bidding can have bidding closed
        $otherProjectId = $this->createProject(['status' => 'open']);
        $this->withSession($session)->get("admin/projects/close-bidding/$otherProjectId");
        $this->seeInDatabase('projects', ['id' => $otherProjectId, 'status' => 'open']);
    }
}