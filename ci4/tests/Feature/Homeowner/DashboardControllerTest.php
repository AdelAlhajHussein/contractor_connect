<?php

namespace Tests\Feature\Homeowner;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use Tests\Support\ProjectTestCase;

class DashboardControllerTest extends ProjectTestCase
{
    public function testIndexShowsDashboard()
    {
        $userId = $this->setUpUser();

        // Attempt
        $result = $this->withSession([
            'user_id'   => $userId,
            'logged_in' => true,
            'role_id'   => 2
        ])->get('homeowner/dashboard');

        // Verify
        $result->assertStatus(200); // loaded successfully
        $result->assertSee('Welcome');
        $result->assertSee('Dashboard'); // dashboard content is visible
        $result->assertSee('Logout');
    }
}