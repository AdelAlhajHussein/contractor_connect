<?php

namespace Tests\Feature\Contractor;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use App\Models\UserModel;

class ProfileControllerTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $refresh   = true;
    protected $namespace = 'App';

    public function testIndexShowsContractorProfileData() {
        $userModel = model(UserModel::class);

        // Create contractor user
        $userId = $userModel->insert([
            'username'      => 'contractor_tester',
            'email'         => 'contractor@test.com',
            'first_name'    => 'Eric',
            'last_name'     => 'Laudrum',
            'phone'         => '555-0199',
            'role_id'       => 3,
            'is_active'     => 1,
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT)
        ]);

        // Create contractor profile
        $this->db->table('contractor_profiles')->insert([
            'contractor_id'   => $userId,
            'address'         => '456 Tech Way',
            'city'            => 'Kitchener',
            'province'        => 'ON',
            'postal_code'     => 'N2G 4M4',
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
        $result->assertSee('contractor_tester');
        $result->assertSee('Eric');
        $result->assertSee('Laudrum');
        $result->assertSee('contractor@test.com');
        $result->assertSee('555-0199');
        $result->assertSee('456 Tech Way');
        $result->assertSee('Kitchener');
        $result->assertSee('N2G 4M4');
        $result->assertSee('approved');
    }

    public function testIndexWorksWithMissingProfileRecord(){
        $userModel = model(UserModel::class);

        // Create a contractor user (without a record in contractor_profiles)
        $userId = $userModel->insert([
            'username'      => 'no_profile_user',
            'email'         => 'incomplete@test.com',
            'first_name'    => 'New',
            'last_name'     => 'Contractor',
            'role_id'       => 3,
            'is_active'     => 1,
            'password_hash' => 'hash'
        ]);

        $result = $this->withSession([
            'user_id'   => (int)$userId,
            'logged_in' => true,
            'role_id'   => 3
        ])->get('contractor/profile');

        // Verify the page still loads
        $result->assertStatus(200);
        $result->assertSee('no_profile_user');
        $result->assertSee('incomplete@test.com');
    }
}