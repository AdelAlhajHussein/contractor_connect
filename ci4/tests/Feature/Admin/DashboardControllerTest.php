<?php

namespace Tests\Feature\Admin;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use Faker\Factory;

class DashboardControllerTest extends CIUnitTestCase {
    use FeatureTestTrait;
    use DatabaseTestTrait;

    protected $refresh = true;
    protected $namespace = 'App';
    private $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
    }

    public function testIndexLoadsSuccessfully()
    {
        // Set up admin session
        $session = [
            'user_id'   => $this->faker->numberBetween(1, 999),
            'logged_in' => true,
            'role_id'   => 1
        ];

        $result = $this->withSession($session)->get('admin/dashboard');

        // Check if you are being redirected away (ex: to login)
        if ($result->isRedirect()) {
            $this->fail('Request was redirected to: ' . $result->getRedirectUrl());
        }

        // Assertions
        $result->assertStatus(200);

    }
}