<?php
namespace Tests\Database;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use App\Models\ProjectModel;
use Faker\Factory as FakerFactory;

class ProjectModelTest extends CIUnitTestCase {
    use DatabaseTestTrait;

    protected $refresh = true;
    protected $namespace = 'App';
    protected $faker;

    protected function setUp(): void {
        parent::setUp();
        $this->faker = FakerFactory::create();
    }

    // ----------------
    // Scenarios
    // ----------------
    // Expect to pass
    /**
     * Scenario: Successfully create a project
     * Expect:
     * - Project record is inserted into DB
     * - Function returns a numeric ID
     * - The title in the DB matches the input
     */
    public function testSuccessfullyCreateProject()
    {
        $model = new ProjectModel();
        $data = [
            'home_owner_id' => 1,
            'category_id'   => 1,
            'title'         => $this->faker->sentence(3),
            'description'   => $this->faker->paragraph,
            'address'       => $this->faker->address,
            'budget_min'    => 1000,
            'budget_max'    => 5000,
            'status'        => 'open'
        ];

        $projectId = $model->insert($data);

        //// Verification
        $this->assertIsNumeric($projectId);
        $this->seeInDatabase('projects', ['title' => $data['title']]);
    }


    // Expect to fail

}