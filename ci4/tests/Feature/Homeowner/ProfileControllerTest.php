<?php

namespace Tests\Feature\Homeowner;

use Tests\Support\ProjectTestCase;

class ProfileControllerTest extends ProjectTestCase
{
    public function testIndexLoadsHomeownerProfile()
    {
        // Create a user in the database to be found by $userModel->find($userId)
        $userId = $this->setUpUser([
            'username'   => 'test_user',
            'email'      => 'test@example.com',
            'role_id'    => 2, // Homeowner
            'is_active'  => 1
        ]);

        // Simulate session and hit the route
        $result = $this->withSession([
            'user_id'   => $userId,
            'logged_in' => true
        ])->get('homeowner/profile');

        // Verify the controller executed successfully
        $result->assertStatus(200);

        // Verify the data passed to the view is present
        $result->assertSee('test_user');
        $result->assertSee('test@example.com');
    }
}