<?php

namespace Tests\Feature\Homeowner;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use App\Models\UserModel;
use App\Models\ProjectModel;
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

    public function testIndexShowsHomeownerProjects()
    {
        $db = \Config\Database::connect();

        // Setup Category
        $db->table('categories')->insert(['name' => $this->faker->word . ' Services']);
        $categoryId = $db->insertId();

        // Setup User manually
        $db->table('users')->insert([
            'username'      => $this->faker->userName,
            'email'         => $this->faker->safeEmail,
            'first_name'    => $this->faker->firstName,
            'last_name'     => $this->faker->lastName,
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT),
            'role_id'       => 3,
            'is_active'     => 1
        ]);
        $homeownerId = $db->insertID();

        // Setup Project linked to this user
        $projectTitle = 'Homeowner Project ' . $this->faker->word;
        $db->table('projects')->insert([
            'home_owner_id' => $homeownerId,
            'category_id'   => $categoryId,
            'title'         => $projectTitle,
            'description'   => $this->faker->paragraph,
            'address'       => $this->faker->address,
            'status'        => 'open'
        ]);

        $result = $this->withSession([
            'user_id'   => (int)$homeownerId,
            'logged_in' => true,
            'role_id'   => 3
        ])->get('homeowner/projects');

        $result->assertStatus(200);
        $result->assertSee($projectTitle);
    }

    public function testDetailsShowsSpecificProject()
    {
        $config = config('Validation');
        $config->ruleSets[] = \App\Validation\ProjectRules::class;

        $userModel = model(UserModel::class);
        $homeownerId = $userModel->insert([
            'username'      => $this->faker->userName,
            'email'         => $this->faker->safeEmail,
            'first_name'    => $this->faker->firstName,
            'last_name'     => $this->faker->lastName,
            'role_id'       => 3,
            'is_active'     => 1,
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT)
        ]);

        $this->db->table('categories')->insert(['name' => $this->faker->word]);
        $catId = $this->db->insertID();

        $projectTitle = 'Unique Detail ' . $this->faker->word;
        $projectModel = model(ProjectModel::class);
        $projectId = $projectModel->insert([
            'home_owner_id' => $homeownerId,
            'category_id'   => $catId,
            'title'         => $projectTitle,
            'description'   => $this->faker->sentence,
            'address'       => $this->faker->address,
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
            'role_id'   => 3
        ])->get("homeowner/projects/$projectId");

        $result->assertStatus(200);
        $result->assertSee($projectTitle);
    }

    public function testNewProjectPageLoadsCategories()
    {
        $categoryName = $this->faker->word . 'ing';
        $this->db->table('categories')->insert([
            'name' => $categoryName
        ]);

        $result = $this->withSession([
            'user_id'   => 123,
            'logged_in' => true,
            'role_id'   => 3
        ])->get('homeowner/projects/new');

        $result->assertStatus(200);
        $result->assertSee($categoryName);
    }

    public function testCreateSavesNewProject()
    {
        $db = \Config\Database::connect();

        // Setup Dependencies
        $db->table('categories')->insert(['name' => $this->faker->word]);
        $categoryId = $db->insertId();

        $db->table('users')->insert([
            'username'      => $this->faker->userName,
            'email'         => $this->faker->safeEmail,
            'first_name'    => $this->faker->firstName,
            'last_name'     => $this->faker->lastName,
            'role_id'       => 3,
            'is_active'     => 1,
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT),
        ]);
        $homeownerId = (int)$db->insertID();

        $projectTitle = 'Project ' . $this->faker->word;

        // The Data Payload
        $projectData = [
            'category_id' => $categoryId,
            'title'       => $projectTitle,
            'description' => $this->faker->sentence,
            'address'     => $this->faker->address,
            'budget_min'  => 1000,
            'budget_max'  => 5000,
             'status'      => 'open',
        ];

        // Execution
        $result = $this->withSession([
            'user_id'   => $homeownerId,
            'logged_in' => true,
            'role_id'   => 3,
        ])->post('homeowner/projects/create', $projectData);

        // Assertions
        $result->assertRedirect();

        $this->seeInDatabase('projects', [
            'title'         => $projectTitle,
            'home_owner_id' => $homeownerId
        ]);
    }
}

// ------------------------------
// Additional validation rules
namespace App\Validation;

class ProjectRules {
    public function greater_than_equal_to_diff($str, string $fields, array $data): bool {
        return true;
    }
}