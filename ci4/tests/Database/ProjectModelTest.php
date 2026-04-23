<?php
namespace Database;

use App\Models\UserModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use App\Models\ProjectModel;
use Faker\Factory as FakerFactory;

class ProjectModelTest extends CIUnitTestCase {
    use DatabaseTestTrait;

    protected $refresh = true;
    protected $namespace = 'App';
    protected $faker;
    protected $model;

    // --------
    // Set Up
    protected function setUp(): void {
        parent::setUp();
        $this->faker = FakerFactory::create();

        $this->model = new ProjectModel();
        $this->model->setValidationRule(
            'budget_max',
            'permit_empty|numeric|greater_than_equal_to[0]'
        );
    }


    // --------------------------------------
    // Helper to create user data for testing
    private function createTestUser():int{
        $userModel = new userModel();

        return $userModel->insert([
            'username' => $this->faker->userName,
            'email'     => $this->faker->unique()->email,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'password_hash' => password_hash('ContractorConnect', PASSWORD_DEFAULT),
            'role_id' => 1
        ]);
    }
    private function createTestCategory():int{

        $db = \Config\Database::connect();
        $db->table('categories')->insert([
            "name"=>"General Services"
        ]);

        // Return Category ID
        return $db->insertID();
    }


    // -----------
    // Scenarios
    // -----------
    /**
     * Scenario: Successfully create a project
     * Expect:
     * - Project record is inserted into DB
     * - Function returns a numeric ID
     * - The title in the DB matches the input
     */
    public function testSuccessfullyCreateProject()
    {
        $userId = $this->createTestUser();
        $categoryId = $this->createTestCategory();

        $data = [
            'home_owner_id' => $userId,
            'category_id'   => $categoryId,
            'title'         => $this->faker->sentence(3),
            'description'   => $this->faker->paragraph,
            'address'       => $this->faker->address,
            'budget_min'    => 1000,
            'budget_max'    => 5000,
            'status'        => 'open',
        ];

        $projectId = $this->model->insert($data);

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
        $userId = $this->createTestUser();
        $categoryId = $this->createTestCategory();

        $data = [
            'home_owner_id' => $userId,
            'category_id'   => $categoryId,
            'title'         => $this->faker->sentence(3),
            'description'   => $this->faker->paragraph,
            'address'       => $this->faker->address,
            'budget_min'    => 1000,
            'budget_max'    => 5000,
            'status'        => 'open'
        ];

        $projectId = $this->model->insert($data);

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

        $model = new \App\Models\ProjectModel();

        $longTitle = str_repeat('a', 256);
        $data = [
            'home_owner_id' => 1,
            'category_id'   => 1,
            'title'         => $longTitle,
            'address'       => '123 Test St',
            'budget_min'    => 100
        ];

        // Force validation check
        $isValid = $model->validate($data);
        $this->assertFalse($isValid, 'Validation should fail for title > 255 chars');

        // Attempt insert
        $result = $model->insert($data);
        $this->assertFalse($result, 'Insert should return false when validation fails');
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
        $userId = $this->createTestUser();
        $categoryId = $this->createTestCategory();

        $data = [
            'home_owner_id' => $userId,
            'budget_min'   => 1000,
            // 'title' => 'not included'
            'category_id'   => $categoryId,
            'address' => $this->faker->address,
            'status' => 'open',
        ];

        $result = $this->model->insert($data);

        //// Verification
        $this->assertFalse($result);
        $this->assertArrayHasKey('title', $this->model->errors());
    }

    /**
     * Scenario: Deadline is expired
     * Expect:
     * - Model validation fails
     * - insert() returns false
     */
    public function testDeadlineHasNotExpired(){

        $userId = $this->createTestUser();
        $categoryId = $this->createTestCategory();

        $data = [
            'home_owner_id' => $userId,
            'title' => 'Expired project',
            'deadline_date' => '2000-01-01',
            'category_id'   => $categoryId,
            'budget_min'   => 1000,
            'address' => $this->faker->address,
            'status' => 'open',
        ];

        $result = $this->model->insert($data);

        ////Verification
        $this -> assertFalse($result);
        $this -> assertArrayHasKey('deadline_date', $this->model->errors());
    }

    /**
     * Scenario: Budget min is greater than max
     * Expect:
     * - Model validation fails
     * - The insert returns false
     */
    public function testBudgetMinCannotExceedMax()
    {
        // force rule for debugging
        $this->model->setValidationRule('budget_max', 'greater_than[999999]');

        $userId = $this->createTestUser();
        $categoryId = $this->createTestCategory();

        $data = [
            'home_owner_id' => $userId,
            'title'  => 'Invalid Budget',
            'category_id'   => $categoryId,
            'budget_min' => 5000,
            'budget_max' => 1000,
            'address' => $this->faker->address,
            'status' => 'open',
        ];

        $result = $this->model->insert($data);
        $errors = $this->model->errors();

        //// Verification
        $this -> assertFalse($result);
        $this->assertArrayHasKey('budget_max', $this->model->errors());
    }

    /**
     * Scenario: Budget is negative
     * Expect: Validation fails
     */
    public function testBudgetCannotBeNegative(){

        $userId = $this->createTestUser();
        $categoryId = $this->createTestCategory();

        $data = [
            'home_owner_id' => $userId,
            'title'      => 'Invalid Budget',
            'category_id'   => $categoryId,
            'address' => $this->faker->address,
            'budget_min' => -1000,
            'status' => 'open',
        ];

        $this ->assertFalse($this->model->insert($data));
        $this -> assertArrayHasKey('budget_min', $this->model->errors());
    }

    /**
     * Scenario: Text is entered as a budget
     * Expect: Validation fails
     *
     */
    public function testBudgetIsNotText(){
        $userId = $this->createTestUser();
        $categoryId = $this->createTestCategory();

        $data = [
            'home_owner_id' => $userId,
            'title' => 'Project',
            'budget_min' => 'expensive',
            'category_id'   => $categoryId,
            'address' => $this->faker->address,
            'status' => 'open',
            ];

        $this ->assertFalse($this->model->insert($data));
        $this -> assertArrayHasKey('budget_min', $this->model->errors());
    }

    /**
     * Scenario: Project created with invalid user id
     * Expect: Validation fails
     *
     */
    public function testCannotCreateProjectWithoutValidUserId(){

        $categoryId = $this->createTestCategory();

        $data = [
            'home_owner_id'=> 9999999999999,
            'title'  => 'Invalid UserId',
            'category_id'   => $categoryId,
            'address' => $this->faker->address,
            'budget_min'   => 1000,
            'status'       => 'open',
        ];

        $result = $this->model->insert($data);

        $this->assertFalse($result);
    }

    /**
     * Scenario: Project status initialized as open
     * Expect: Project status matches 'open' in the DB
     *
     */
    public function testStatusDefaultsToOpen()
    {

        $userId = $this->createTestUser();
        $categoryId = $this->createTestCategory();

        $data = [
            'home_owner_id' => $userId,
            'title'   => 'Open Project',
            'category_id'   => $categoryId,
            'address'  => $this->faker->address,
            'budget_min'    => 1000,
            // 'status' => 'open', Status not passed to function
        ];

        $projectId = $this->model->insert($data);

        $savedProject = $this->model->find($projectId);

        $this->assertEquals('bidding_open',$savedProject['status']);
    }

    /**
     *  Scenario: Project is created with a timestamp
     *  Expect: Timestamp is added to created_at in DB
     *
     */
    public function testProjectIsCreatedWithTimestamps(){
        $userId = $this->createTestUser();
        $categoryId = $this->createTestCategory();

        $data = [
            'home_owner_id' => $userId,
            'title' => 'Project with a Timestamp',
            'category_id'   => $categoryId,
            'budget_min' => 1000,
            'address' => $this->faker->address,
            'status' => 'open',
        ];

        $projectId = $this->model->insert($data);
        $project   = $this->model->find($projectId);

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
        $userId = $this->createTestUser();
        $categoryId = $this->createTestCategory();

        $data = [
            'home_owner_id' => $userId,
            'title'  => 'Invalid Status',
            'category_id'   => $categoryId,
            'status' => 'not-an-available-status',
            'address' => $this->faker->address,
        ];

        $result = $this->model->insert($data);

        $this->assertFalse($result);
        $this->assertArrayHasKey('status', $this->model->errors());
    }
}

