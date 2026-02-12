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
    protected $faker;

    protected function setUp(): void{
        parent::setUp();
        $this->faker = FakerFactory::create();
    }
    public function testCanInsertUser()
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
}