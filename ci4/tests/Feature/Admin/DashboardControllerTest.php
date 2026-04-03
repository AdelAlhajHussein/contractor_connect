<?php

namespace Tests\Feature\Admin;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

class DashboardControllerTest extends CIUnitTestCase
{
    use FeatureTestTrait;
    use DatabaseTestTrait;

    protected $refresh = true;
    protected $namespace = 'App';
    protected $migrate = true;

    // Helper
    private function createTestUser(array $data = [])
    {
        $defaults = [
            'username' => 'test_user',
            'email' => 'test@example.com',
            'first_name'=>'Test',
            'last_name'=>'User',
            'role_id' => 2,
            'is_active' => 1,
            'password_hash' => 'fake_hash'
        ];

        return $this->db->table('users')->insert(array_merge($defaults, $data));
    }

    // Test
    public function testIndexLoadsDashboardWithUsers(){

        $this->createTestUser(['username' => 'admin_view_user']);

        $result = $this->withSession(['logged_in' => true, 'role_id' => 1])
            ->get('admin/dashboard');

        $result->assertStatus(200);
        $result->assertSee('Admin Dashboard');
        $result->assertSee('admin_view_user');
    }
}