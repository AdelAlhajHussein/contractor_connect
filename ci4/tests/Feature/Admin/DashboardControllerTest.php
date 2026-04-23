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
            'user_id'   => 999,
            'logged_in' => true,
            'role_id'   => 1
        ];

        $result = $this->withSession($session)->get('admin/dashboard');

        // 1. Check if you are being redirected away (e.g., to login)
        if ($result->isRedirect()) {
            $this->fail('Request was redirected to: ' . $result->getRedirectUrl());
        }

        // Assertions
        $result->assertStatus(200);

    }
}