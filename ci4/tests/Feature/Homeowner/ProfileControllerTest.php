<?php

namespace Tests\Feature\Homeowner;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use App\Models\UserModel;
use App\Models\HomeOwnerProfileModel;

class ProfileControllerTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $refresh   = true;
    protected $namespace = 'App';

    public function testIndexShowsUserProfileAndProfileData()
    {
        $userModel = model(UserModel::class);

        // Create homeowner user
        $userId = $userModel->insert([
            'username'      => 'eric_profile_test',
            'email'         => 'eric_profile@test.com',
            'first_name'    => 'Eric',
            'last_name'     => 'Laudrum',
            'role_id'       => 2,
            'is_active'     => 1,
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT)
        ]);

        $profileModel = model(HomeOwnerProfileModel::class);

        // Create their profile
        $profileModel->insert([
            'user_id'       => $userId,
            'home_owner_id' => $userId,
            'first_name'    => 'Eric',
            'last_name'     => 'Laudrum',
            'address'       => '123 Main St',
            'city'          => 'Toronto',
            'province'      => 'ON',
            'postal_code'   => 'M5V 2L7'
        ]);

        // Attempt to access profile
        $result = $this->withSession([
            'user_id'   => (int)$userId,
            'logged_in' => true,
            'role_id'   => 2
        ])->get('homeowner/profile');

        // Assertions
        $result->assertStatus(200);
        $result->assertSee('Eric');
    }
}