<?php

namespace Tests\Feature\Admin;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

class UserControllerTest extends CIUnitTestCase{
    use FeatureTestTrait, DatabaseTestTrait;

    protected $refresh = true;
    protected $namespace = 'App';

    public function testIndexLoadsSuccessfullyForAdmin(){

        // Create admin user for the db
        $db = \Config\Database::connect();
        $db->table('users')->insert([
            'username' => 'admin_user',
            'email' => 'admin@example.com',
            'password_hash' => password_hash('Admin123', PASSWORD_DEFAULT),
            'role_id' => 1,
            'is_active' => 1,
            'first_name' => 'Admin',
            'last_name' => 'User',
        ]);

        // Attempt login
        $result = $this->withSession([
            'logged_in' => true,
            'role_id' => 1,
            'user_id' => 1,

        ])->get('/admin/users');

        // Check if it worked
        $result->assertStatus(200);
        $result->assertSee('admin_user');
    }

}