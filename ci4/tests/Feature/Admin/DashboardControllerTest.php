<?php

namespace Tests\Feature\Admin;

use CodeIgniter\Test\FeatureTestTrait;
use Tests\Support\ProjectTestCase;

class DashboardControllerTest extends ProjectTestCase
{
    use FeatureTestTrait;
    public function testIndexLoadsSuccessfully()
    {
        // Set up admin session
        $session = [
            'logged_in' => true,
            'role_id' => 1
        ];

        // Attempt to call index
        $result = $this->withSession($session)
            ->get('admin/dashboard');

        // Verify page loads
        $result->assertStatus(200);

        // Verify content
        $html = $result->getResponseBody();
        $this->assertStringContainsString('Dashboard', $html);
    }
}