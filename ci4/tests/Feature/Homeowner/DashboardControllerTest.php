<?php

namespace Tests\Feature\Homeowner;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use Config\Services;

class DashboardControllerTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $refresh = true;
    protected $namespace = 'App';

    public function testIndexShowsDashboard()
    {
        // Define the fake data
        $fakeUser = [
            'id'         => 1,
            'username'   => 'test_homeowner',
            'first_name' => 'Eric',
            'last_name'  => 'L',
            'role_id'    => 2
        ];

        // Create the Mock
        $userModel = $this->createMock(\App\Models\UserModel::class);
        $userModel->method('find')->willReturn($fakeUser);

        // Inject the mock into Services
        Services::injectMock('userModel', $userModel);

        // Attempt the request
        $result = $this->withSession([
            'user_id'   => 1,
            'logged_in' => true,
            'role_id'   => 2
        ])->get('homeowner/dashboard');

        // Verify results
        $result->assertStatus(200);
        $result->assertSee('Eric');
    }
}