<?php

namespace Tests\Feature\Admin;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

class UserControllerTest extends CIUnitTestCase{
    use FeatureTestTrait, DatabaseTestTrait;

    protected $refresh = true;
    protected $namespace = 'App';

    // Index Tests
    public function testIndexLoadsSuccessfullyForAdmin(){

        // Create an admin account to test:
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

    public function testIndexFiltersSearchAndRole(){
        $db = \Config\Database::connect();

        // Create two users accounts to test
        $db->table('users')->insertBatch([
            [
                'username' => 'admin_user',
                'email' => 'user_one@example.com',
                'password_hash' => password_hash('user_one', PASSWORD_DEFAULT),
                'role_id' => 1,
                'is_active' => 1,
                'first_name' => 'User',
                'last_name' => 'One',
            ],
            [
                'username' => 'homeowner_user',
                'email' => 'user_two@example.com',
                'password_hash' => password_hash('user_two', PASSWORD_DEFAULT),
                'role_id' => 2,
                'is_active' => 1,
                'first_name' => 'User',
                'last_name' => 'Two',
            ]
        ]);

        // Test Admin can search for user
        $result = $this->withSession(['logged_in' => true, 'role_id' => 1])
            ->get('/admin/users?q=homeowner&role_id=2');

        // Assertions
        $result->assertStatus(200);
        $result->assertSee('homeowner_user');
        $result->assertDontSee('admin_user');
    }

    public function testIndexFiltersByStatus(){
        $db = \Config\Database::connect();

        // Create an active and inactive account to test
        $db->table('users')->insertBatch([
            [
                'username' => 'user_one',
                'email' => 'user_one@example.com',
                'password_hash' => password_hash('Admin123', PASSWORD_DEFAULT),
                'role_id' => 2,
                'is_active' => 1,
                'first_name' => 'Active',
                'last_name' => 'User',
            ],
            [
                'username' => 'user_two',
                'email' => 'user_two@example.com',
                'password_hash' => password_hash('user_two', PASSWORD_DEFAULT),
                'role_id' => 2,
                'is_active' => 0,
                'first_name' => 'Inactive',
                'last_name' => 'User',
            ]
        ]);

        // Filter inactive accounts
        $result = $this->withSession(['logged_in' => true, 'role_id' => 1])
            ->get('/admin/users?status=0');

        // Assertions
        $result->assertStatus(200);
        $result->assertSee('inactive_user');
        $result->assertDontSee('active_user');

    }

    // Toggle Tests
    public function testToggleRedirectsIfUserNotFound(){

        // Attempt to toggle to non-existent id
        $result = $this->withSession(['logged_in' => true, 'role_id' => 1])
            ->get('/admin/users/toggle/9999999');

        // Assertions
        $result->assertStatus(302);
        $result->assertRedirectTo(site_url('admin/users'));
    }

    public function testToggleChangeUserStatus(){
    $db = \Config\Database::connect();

    // Create an account to test changing active status
    $db->table('users')->insert([
        'username' => 'toggle_user',
        'email' => 'toggle_user@example.com',
        'password_hash' => password_hash('toggle_user', PASSWORD_DEFAULT),
        'role_id' => 2,
        'is_active' => 1,
        'first_name' => 'Toggle',
        'last_name' => 'User',
    ]);

    $userId = $db->insertID();

    // Attempt to toggle status
    $result = $this->withSession(['logged_in' => true, 'role_id' => 1])
        ->get('/admin/users/toggle/'.$userId);

    // Assertions
    $result->assertRedirectTo(site_url(
        'admin/users'
    ));

    // Verify status changed to 0
    $this->seeInDatabase('users', [
        'id' => $userId,
        'is_active' => 0,
    ]);

    }

    // Update Role Tests
    public function testUpdateRoleFailsWithInvalidRoleId(){

        $result = $this->withSession(['logged_in' => true, 'role_id' => 1])
            ->post('admin/users/updateRole/1', ['role_id' => 999]);

        // Verify redirect on invalid role
        $result->assertStatus(302);
        $result->assertRedirectTo(site_url('admin/users'));
    }

    public function testUpdateRoleRedirectsIfUserNotFound(){

        // Attempt to add an invalid user with a valid role
        $result = $this->withSession(['logged_in' => true, 'role_id' => 1])
            ->post('admin/users/updateRole/999', ['role_id' => 2]);

        // Verify site redirects if
        $result->assertStatus(302);
        $result->assertRedirectTo(site_url('admin/users'));
    }





}