<?php

namespace Tests\Feature\Contractor;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use App\Models\UserModel;

class DashboardControllerTest extends CIUnitTestCase {
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $refresh   = true;
    protected $namespace = 'App';

    public function testIndexShowsContractorDashboard()
    {
        $userModel = model(UserModel::class);

        $userData = [
            'username'      => 'dash_tester',
            'first_name'    => 'John',
            'last_name'     => 'Contractor',
            'email'         => 'john@example.com',
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT),
            'role_id'       => 3,
            'is_active'     => 1
        ];

        $contractorId = $userModel->insert($userData);

        // Attempt the request
        $result = $this->withSession([
            'user_id'   => $contractorId,
            'logged_in' => true,
            'role_id'   => 3
        ])->get('contractor/dashboard');

        // Assertions
        $result->assertStatus(200);
        $result->assertSee('dash_tester');
        $result->assertSee('John');
        $result->assertSee('Contractor');
        $result->assertSee('john@example.com');
    }
}