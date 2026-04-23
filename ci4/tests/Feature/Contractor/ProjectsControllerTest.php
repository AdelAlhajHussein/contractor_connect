<?php

namespace Tests\Feature\Contractor;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use App\Models\UserModel;
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


    public function testIndexShowsContractorProjects()
    {
        $userModel = model(UserModel::class);

        // Create a homeowner user
        $homeOwnerId = $userModel->insert([
            'username'   => $this->faker->userName,
            'email'      => $this->faker->safeEmail,
            'first_name' => $this->faker->firstName,
            'last_name'  => $this->faker->lastName,
            'role_id'    => 2,
            'is_active'  => 1,
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT)
        ]);

        // Create a category
        $categoryName = $this->faker->word;
        $this->db->table('categories')->insert([
            'name' => $categoryName
        ]);
        $categoryId = $this->db->insertID();

        // Create a contractor user
        $contractorId = $userModel->insert([
            'username'   => $this->faker->userName,
            'email'      => $this->faker->safeEmail,
            'first_name' => $this->faker->firstName,
            'last_name'  => $this->faker->lastName,
            'role_id'    => 3,
            'is_active'  => 1,
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT)
        ]);

        // Create a project
        $projectTitle = $this->faker->sentence(3);
        $this->db->table('projects')->insert([
            'home_owner_id' => $homeOwnerId,
            'category_id'   => $categoryId,
            'title'         => $projectTitle,
            'description'   => $this->faker->paragraph,
            'address'       => $this->faker->address,
            'budget_min'    => 500.00,
            'budget_max'    => 1000.00,
            'status'        => 'open',
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);
        $projectId = $this->db->insertID();

        // Link contractor to a bid
        $bidAmount = 500.00;
        $this->db->table('bids')->insert([
            'project_id'    => $projectId,
            'contractor_id' => $contractorId,
            'bid_amount'    => $bidAmount,
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
        $result->assertSee($projectTitle);
        $result->assertSee((string)$bidAmount);
    }
}