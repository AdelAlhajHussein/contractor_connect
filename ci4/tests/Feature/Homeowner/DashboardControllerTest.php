<?php

namespace Tests\Feature\Homeowner;

use Tests\Support\ProjectTestCase;

class HomeownerDashboardControllerTest extends ProjectTestCase
{
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

        // Create a Mock UserModel
        $userModel = $this->createMock(\App\Models\UserModel::class);

        // When the controller calls find(1), return fake user
        $userModel->method('find')->willReturn($fakeUser);

        // Inject this mock into the framework services
        \Config\Services::injectMock('userModel', $userModel);

        // Run the request with the session keys
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