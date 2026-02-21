<?php
namespace Database;

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

    /**
     * // TODO: verify if budget can be 0
     * Tests
     * - project created with created date / timestamps
     *
     */


    // ----------------
    // Scenarios
    // ----------------
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
            'status'        => 'open',
        ];

        $projectId = $model->insert($data);

        //// Verification
        $this->assertIsNumeric($projectId);
        $this->seeInDatabase('projects', ['title' => $data['title']]);
    }

    /**
     * Scenario: Successfully create a project without an end date
     * Expect:
     * - Project record is inserted into DB
     * - Function returns a numeric ID
     * - The title in the DB matches the input
     * - the end date value is null
     */
    public function testSuccessfullyCreateProjectWithoutEndDate()
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

    /**
     * Scenario: Project title exceeds the 255 character limit
    * Expect:
    * - Model validation fails
    * - Insert returns false
    * - Error message for title
    */
    public function testTitleExceedsCharacterLimit(){
        $model = new ProjectModel();

        $title = $this->faker->realText(260);

        $data =[
            'home_owner_id' => 1,
            'title'         => $title,
            'budget_min'    => 1000,
            'budget_max'    => 5000,
            'status'        => 'open',
        ];

        $result = $model->insert($data);

        ////Verification
        $this->assertFalse($result, "The title exceeds the 255 character limit");
        $this->assertArrayHasKey('title', $model->errors());
    }

    /**
     * Scenario: Project created without title
     * Expect:
     * - Model validation fails
     * - insert() returns false
     * - Error message exists for the title field
     */
    public function testInsertFailsWithoutTitle()
    {
        $model = new ProjectModel();
        $data = [
            'home_owner_id' => 1,
            'budget_min'   => 1000,
            // 'title' => 'not included'
        ];

        $result = $model->insert($data);

        //// Verification
        $this->assertFalse($result);
        $this->assertArrayHasKey('title', $model->errors());
    }

    /**
     * Scenario: Deadline is expired
     * Expect:
     * - Model validation fails
     * - insert() returns false
     */
    public function testDeadlineHasNotExpired(){
        $model = new ProjectModel();

        $data = [
            'title'         => 'Expired project',
            'deadline_date' => '2000-01-01',
        ];

        $result = $model->insert($data);

        ////Verification
        $this -> assertFalse($result);
        $this -> assertArrayHasKey('deadline_date', $model->errors());
    }

    /**
     * Scenario: Budget min is greater than max
     * Expect:
     * - Model validation fails
     * - The insert returns false
     */
    public function testBudgetMinCannotExceedMax()
    {
        $model = new ProjectModel();
        $data = [
            'title'      => 'Invalid Budget',
            'budget_min' => 5000,
            'budget_max' => 1000,
        ];

        $result = $model->insert($data);

        //// Verification
        $this->assertFalse($result, 'The model should reject a min budget that exceeds the max budget');
    }

    /**
     * Scenario: Budget is negative
     * Expect: Validation fails
     */
    public function testBudgetCannotBeNegative(){
        $model = new ProjectModel();
        $data = [
            'title'      => 'Invalid Budget',
            'budget_min' => -1000,
        ];

        $this ->assertFalse($model->insert($data));
        $this -> assertArrayHasKey('budget_min', $model->errors());
    }

    /**
     * Scenario: Text is entered as a budget
     * Expect: Validation fails
     *
     */
    public function testBudgetIsNotText(){
        $model = new ProjectModel();
        $data = [
            'title' => 'Project',
            'budget_min' => 'expensive',
            ];

        $this ->assertFalse($model->insert($data));
        $this -> assertArrayHasKey('budget_min', $model->errors());
    }

    /**
     * Scenario: Project created with invalid user id
     * Expect: Validation fails
     *
     */
    public function testCannotCreateProjectWithoutValidUserId(){
        $model = new ProjectModel();

        $data = [
            'home_owner_id'=> 99999,
            'title'        => 'Invalid UserId',
            'budget_min'   => 1000,
            'status'       => 'open',
        ];

        $result = $model->insert($data);

        $this->assertFalse($result);
    }

    /**
     * Scenario: Project status initialized as open
     * Expect: Project status matches 'open' in the DB
     *
     */
    public function testStatusDefaultsToOpen()
    {
        $model = new ProjectModel();
        $data = [
            'home_owner_id' => 1,
            'title'         => 'Open Project',
            'budget_min'    => 1000,
        ];

        $projectId = $model->insert($data);

        $savedProject = $model->find($projectId);

        $this->assertEquals('open',$savedProject['status']);
    }

    /**
     *  Scenario: Project is created with a timestamp
     *  Expect: Timestamp is added to created_at in DB
     *
     */
    public function testProjectIsCreatedWithTimestamps(){
        $model = new ProjectModel();
        $data = [
            'home_owner_id' => 1,
            'title' => 'Project with a Timestamp',
            'budget_min' => 1000,
        ];

        $projectId = $model->insert($data);
        $project   = $model->find($projectId);

        $this->assertNotNull($project['created_at']);
        $this->assertNotNull($project['updated_at']);
    }

    /**
     * Scenario: Project status is not one of the valid options
     * Expect:
     * - Model validation fails
     * - Insert returns false
     * - Error message is created re- status
     */
    public function testProjectStatusValueIsValid(){
        $model = new ProjectModel();
        $data = [
            'title'  => 'Invalid Status',
            'status' => 'hacker-level-status', // Not in ['open', 'in-progress', 'closed']
        ];

        $result = $model->insert($data);

        $this->assertFalse($result);
        $this->assertArrayHasKey('status', $model->errors());
    }
}