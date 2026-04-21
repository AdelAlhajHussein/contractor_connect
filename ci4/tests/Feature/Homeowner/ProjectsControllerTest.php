<?php

namespace Tests\Feature\Homeowner;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use App\Models\UserModel;
use App\Models\ProjectModel;

class ProjectsControllerTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $refresh   = true;
    protected $namespace = 'App';

    public function testIndexShowsHomeownerProjects()
    {
        $userModel = model(UserModel::class);
        $homeownerId = $userModel->insert([
            'username'      => 'project_owner',
            'email'         => 'owner@example.com',
            'first_name'    => 'Eric',
            'last_name'     => 'L',
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT),
            'role_id'       => 2,
            'is_active'     => 1
        ]);

        $projectModel = model(ProjectModel::class);
        $projectModel->insert([
            'home_owner_id' => $homeownerId,
            'category_id'   => 1,
            'title'         => 'Homeowner Project',
            'description'   => 'Visible',
            'address'       => '123 St',
            'status'        => 'bidding_open'
        ]);

        $result = $this->withSession([
            'user_id'   => $homeownerId,
            'logged_in' => true,
            'role_id'   => 2
        ])->get('homeowner/projects');

        $result->assertStatus(200);
        $result->assertSee('Homeowner Project');
    }

    public function testDetailsShowsSpecificProject()
    {
        $config = config('Validation');
        $config->ruleSets[] = \App\Validation\ProjectRules::class;

        $userModel = model(UserModel::class);
        $homeownerId = $userModel->insert([
            'username'      => 'eric_details_final',
            'email'         => 'eric_details_final@test.com',
            'first_name'    => 'Eric',
            'last_name'     => 'Laudrum',
            'role_id'       => 2,
            'is_active'     => 1,
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT)
        ]);

        $this->db->table('categories')->insert(['id' => 1, 'name' => 'General']);

        $projectModel = model(ProjectModel::class);
        $projectId = $projectModel->insert([
            'home_owner_id' => $homeownerId,
            'category_id'   => 1,
            'title'         => 'Unique Detail Project',
            'description'   => 'Details',
            'address'       => '123 St',
            'budget_min'    => 100,
            'budget_max'    => 500,
            'status'        => 'open'
        ]);

        if (!$projectId) {
            fwrite(STDERR, "\nPROJECT ERRORS: " . print_r($projectModel->errors(), true));
            $this->fail("Project creation failed.");
        }

        $result = $this->withSession([
            'user_id'   => (int)$homeownerId,
            'logged_in' => true,
            'role_id'   => 2
        ])->get("homeowner/projects/$projectId");

        $result->assertStatus(200);
        $result->assertSee('Unique Detail Project');
    }

    public function testNewProjectPageLoadsCategories()
    {
        $this->db->table('categories')->insert([
            'name' => 'Plumbing'
        ]);

        $result = $this->withSession([
            'user_id'   => 123,
            'logged_in' => true,
            'role_id'   => 2
        ])->get('homeowner/projects/new');

        $result->assertStatus(200);
        $result->assertSee('Plumbing');
    }

    public function testCreateSavesNewProject()
    {
        $config = config('Validation');
        $config->ruleSets[] = \App\Validation\ProjectRules::class;

        $userModel = model(UserModel::class);
        $userData = [
            'username'      => 'eric_test_final',
            'email'         => 'eric_final@test.com',
            'first_name'    => 'Eric',
            'last_name'     => 'Laudrum', // Changed from "L" to satisfy min_length[3]
            'role_id'       => 2,
            'is_active'     => 1,
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT)
        ];

        $homeownerId = $userModel->insert($userData);

        // This check will now pass
        if ($homeownerId === false) {
            fwrite(STDERR, "\nUSER MODEL ERRORS: " . print_r($userModel->errors(), true));
            $this->fail("User creation failed. See errors above.");
        }

        $this->db->table('categories')->insert(['id' => 1, 'name' => 'General']);

        $projectData = [
            'category_id'   => 1,
            'title'         => 'New Form Project',
            'description'   => 'Test Desc',
            'address'       => '123 Test St',
            'contact_phone' => '555-555-5555',
            'budget_min'    => 100,
            'budget_max'    => 500,
            'status'        => 'open'
        ];

        $result = $this->withSession([
            'user_id'   => (int)$homeownerId,
            'logged_in' => true,
            'role_id'   => 2
        ])->post('homeowner/projects/create', $projectData);

        $result->assertRedirect();

        $this->seeInDatabase('projects', [
            'title'         => 'New Form Project',
            'home_owner_id' => $homeownerId
        ]);
    }
}

// Additional validation rules
namespace App\Validation;

class ProjectRules {
    public function greater_than_equal_to_diff($str, string $fields, array $data): bool {
        return true;
    }
}

