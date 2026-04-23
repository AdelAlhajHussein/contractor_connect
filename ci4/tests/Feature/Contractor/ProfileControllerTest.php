<?php

namespace Tests\Feature\Contractor;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use App\Models\UserModel;
use Faker\Factory;

class ProfileControllerTest extends CIUnitTestCase
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

    public function testIndexShowsContractorProfileData() {
        $userModel = model(UserModel::class);

        // Create contractor user
        $username = $this->faker->userName;
        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastName;
        $email = $this->faker->safeEmail;
        $phone = $this->faker->phoneNumber;
        $address = $this->faker->address;
        $city = $this->faker->city;
        $postal = $this->faker->postcode;

        $userId = $userModel->insert([
            'username'      => $username,
            'email'         => $email,
            'first_name'    => $firstName,
            'last_name'     => $lastName,
            'phone'         => $phone,
            'role_id'       => 3,
            'is_active'     => 1,
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT)
        ]);

        // Create contractor profile
        $this->db->table('contractor_profiles')->insert([
            'contractor_id'   => $userId,
            'address'         => $address,
            'city'            => $city,
            'province'        => 'ON',
            'postal_code'     => $postal,
            'approval_status' => 'approved'
        ]);

        // Attempt to access the route
        $result = $this->withSession([
            'user_id'   => (int)$userId,
            'logged_in' => true,
            'role_id'   => 3
        ])->get('contractor/profile');

        // Assertions
        $result->assertStatus(200);
        $result->assertSee($username);
        $result->assertSee($firstName);
        $result->assertSee($lastName);
        $result->assertSee($email);
        // We use string cast for phone in case Faker format varies
        $result->assertSee((string)$phone);
        $result->assertSee($address);
        $result->assertSee($city);
        $result->assertSee($postal);
        $result->assertSee('approved');
    }

    public function testIndexWorksWithMissingProfileRecord(){
        $userModel = model(UserModel::class);

        // DEBUGGING - clear existing sessions
        session()->destroy();

        $username = $this->faker->userName;
        $email = $this->faker->safeEmail;

        // Create contractor user
        $userId = $userModel->insert([
            'username'      => $username,
            'email'         => $email,
            'first_name'    => $this->faker->firstName,
            'last_name'     => $this->faker->lastName,
            'phone'         => $this->faker->phoneNumber,
            'role_id'       => 3,
            'is_active'     => 1,
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT)
        ]);

        // DEBUGGING - Force user_id to int
        $userId = (int)$userId;

        $result = $this->withSession([
            'user_id'   => $userId,
            'logged_in' => true,
            'role_id'   => 3
        ])->get('contractor/profile');

        // Assertions
        $result->assertStatus(200);
        $result->assertSee($username);
        $result->assertSee($email);
    }
}