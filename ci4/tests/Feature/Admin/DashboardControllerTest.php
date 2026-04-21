<?php

namespace Tests\Feature\Admin;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

class DashboardControllerTest extends CIUnitTestCase {
    use FeatureTestTrait;
    use DatabaseTestTrait;

    protected $refresh = true;
    protected $namespace = 'App';

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
        if ($html === null) {
            $this->fail("The response body is null. Check if DashboardController returns the view.");
        }

        $this->assertStringContainsString('Dashboard', $html);
    }
}