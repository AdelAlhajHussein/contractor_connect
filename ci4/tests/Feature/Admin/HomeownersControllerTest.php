<?php

namespace Tests\Feature\Admin;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use App\Models\UserModel;
use App\Models\HomeOwnerProfileModel;

class HomeownersControllerTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $refresh   = true;
    protected $namespace = 'App';

    public function testIndexShowsHomeowners(){

        // Create a homeowner user
        $userModel = model(UserModel::class);
        $userData = [
            'username'      => 'homeowner_eric',
            'email'         => 'eric@homeowner.com',
            'first_name'    => 'Eric',
            'last_name'     => 'L',
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT),
            'role_id'       => 2,
            'is_active'     => 1
        ];
        $userId = $userModel->insert($userData);

        // Create their profile
        $profileModel = model(HomeOwnerProfileModel::class);
        $profileModel->insert([
            'home_owner_id' => $userId,
            'address'       => '123 Test St',
            'city'          => 'Toronto',
            'province'      => 'ON',
            'postal_code'   => 'M5V 2L7'
        ]);

        // Setup Admin session to access the Admin panel
        $session = [
            'user_id'   => 999,
            'logged_in' => true,
            'role_id'   => 1
        ];

        // Attempt route
        $result = $this->withSession($session)->get('admin/homeowners');

        // Assertions
        $result->assertStatus(200);

        // Check the HTML for the user we just created
        $result->assertSee('homeowner_eric');
        $result->assertSee('eric@homeowner.com');
    }
}