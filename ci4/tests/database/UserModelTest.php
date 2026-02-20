<?php
namespace Tests\Database;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use App\Models\UserModel;
use Faker\Factory as FakerFactory;

class UserModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $refresh = true;
    protected $namespace = 'App';
    protected $faker; //

    protected function setUp(): void{
        parent::setUp();
        $this->faker = FakerFactory::create();
    }

    // Scenarios
    /**
     * Scenario: Successfully insert a new user
     * Expect:
     * - The model returns a numeric ID
     * - The user data can be verified in the db
     */
    public function testInsertUser()
    {
        $model = new UserModel();
        $data = [
            'username'   => $this->faker->userName,
            'email'      => $this->faker->email,
            'first_name' => $this->faker->firstName,
            'last_name'  => $this->faker->lastName,
            'role_id'    => 1,
            'is_active'  => 1
        ];

        // Attempt to insert data
        $userId = $model->insert($data);

        // Verify ID is a number
        $this->assertIsNumeric($userId);

        // Verify data exists in db
        $this->seeInDatabase('users', ['email' => $data['email']]);
    }

    /**
     * Scenario: Attempt to insert a user with an email that already exists
     * Expect:
     * - Model validation fails
     * - insert() returns false
     */
    public function testCannotCreateUserWithExistingEmail()
    {
        $model = new UserModel();
        $fakeEmail = $this->faker->unique()->email;

        // 1. Insert the first user successfully
        $model->insert([
            'username'   => $this->faker->userName,
            'email'      => $fakeEmail,
            'role_id'    => 1,
            'first_name' => $this->faker->firstName,
        ]);

        // 2. Attempt to insert a second user with the same email
        $data = [
            'username'   => $this->faker->userName,
            'email'      => $fakeEmail,
            'role_id'    => 1,
            'first_name' => $this->faker->firstName,
        ];

        $result = $model->insert($data);

        // Verify insert returns false for duplicate email
        $this->assertFalse($result, 'The model should not allow duplicate emails.');
    }

    /**
     * Scenario: Use soft delete on a user account
     * Expect:
     * - find() returns null for the ID
     * - The record still exists in the database
     * - The deleted_at column is not null
     */
    public function testSoftDeleteWorks()
    {
        $model = new UserModel();

        // Create random users to delete
        $email = $this->faker->unique()->email;
        $data = [
            'username'   => $this->faker->userName,
            'email'      => $email,
            'role_id'    => 1,
            'first_name' => $this->faker->firstName,
        ];

        $id = $model->insert($data);

        // Delete user
        $model->delete($id);

        // Verify record has been deleted
        $this->assertNull($model->find($id));

        // Verify user is still in the database (Not a hard delete)
        $this->seeInDatabase('users', [
            'id'    => $id,
            'email' => $email
        ]);

        // Verify the deleted_at column is no longer NULL
        $this->dontSeeInDatabase('users', [
            'id'         => $id,
            'deleted_at' => null
        ]);
    }

    /**
     * Scenario: Successfully find a user by email
     * Expect:
     * - The query returns an array of user data
     * - The email returned matches the queried email
 */
    public function testFindUserByEmail()
    {
        $model = new UserModel();
        $fakeEmail = $this->faker->unique()->email;

        // Create user
        $model -> insert([
            'username'   => $this->faker->userName,
            'email'      => $fakeEmail,
            'first_name' => $this->faker->firstName,
            'role_id'    => 1,
        ]);

        // Attempt to find by email
        $user = $model->where('email', $fakeEmail)->first();

        // Verify array exists and email matches
        $this->assertIsArray($user);
        $this->assertEquals($fakeEmail, $user['email']);
    }

    /**
     * Scenario: Password is too short
     * Expect:
     * - Model validation fails
     * - insert() returns false
     * - The model returns an error message for password_hash
     */
    public function testPasswordTooShort(){
        $model = new UserModel();

        $data = [
            'username'      => $this->faker->userName,
            'email'         => $this->faker->email,
            'password_hash' => 'pwd',
            'role_id'       => 1
        ];

    $result = $model ->insert($data);

    ////Verification
    // Password entry failed
    $this->assertFalse($result);

    // Error message displayed to the user
    $this->assertArrayHasKey('password_hash', $model->errors());

    }
}