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

        $userId = $model->insert($data);

        // Verify ID is a number
        $this->assertIsNumeric($userId);

        // Verify data exists in db
        $this->seeInDatabase('users', ['email' => $data['email']]);
    }
}