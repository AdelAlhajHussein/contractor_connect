<?php

namespace Tests\Feature\Homeowner;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use App\Models\UserModel;
use App\Models\HomeOwnerProfileModel;
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

    public function testIndexShowsUserProfileAndProfileData()
    {
        $userModel = model(UserModel::class);

        // Create homeowner user
        $firstName = $this->faker->firstName;
        $userId = $userModel->insert([
            'username'      => $this->faker->userName,
            'email'         => $this->faker->safeEmail,
            'first_name'    => $firstName,
            'last_name'     => $this->faker->lastName,
            'role_id'       => 2,
            'is_active'     => 1,
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT),
        ]);

        $profileModel = model(HomeOwnerProfileModel::class);

        // Create their profile
        $profileModel->insert([
            'user_id'       => $userId,
            'home_owner_id' => $userId,
            'first_name'    => $firstName,
            'last_name'     => $this->faker->lastName,
            'address'       => $this->faker->address,
            'city'          => $this->faker->city,
            'province'      => 'ON',
            'postal_code'   => $this->faker->postcode,
        ]);

        // Attempt to access profile
        $result = $this->withSession([
            'user_id'   => (int)$userId,
            'logged_in' => true,
            'role_id'   => 2
        ])->get('homeowner/profile');

        // Assertions
        $result->assertStatus(200);
        $result->assertSee($firstName);
    }
}