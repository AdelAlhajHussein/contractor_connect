<?php

namespace Tests\Feature\Admin;

use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\CIUnitTestCase;

final class ReportsControllerTest extends CIUnitTestCase{
    use DatabaseTestTrait, FeatureTestTrait;

    protected $refresh = true;
    protected $namespace = 'App';


    public function testIndexReturnsSuccessAndCorrectData(){
        $db = db_connect();

        // Create users to test
        $db->table('users')->insertBatch([
            [
                'email' => 'admin@test.com',
                'username' => 'admin',
                'first_name' => 'Admin',
                'last_name' => 'User',
                'role_id' => 1,
                'is_active' => 1,
                'password_hash' => password_hash('admin', PASSWORD_DEFAULT),
            ],
            [
                'email' => 'user1@test.com',
                'username' => 'user1',
                'first_name' => 'First',
                'last_name' => 'User',
                'role_id' => 2,
                'is_active' => 1,
                'password_hash' => password_hash('user1password', PASSWORD_DEFAULT),
            ],
            [
                'email' => 'user2@test.com',
                'username' => 'user2',
                'first_name' => 'Second',
                'last_name' => 'User',
                'role_id' => 2,
                'is_active' => 1,
                'password_hash' => password_hash('user2password', PASSWORD_DEFAULT),
            ],
        ]);

        // Project category
        $db->table('categories')->insert([
            'name'=>'Plumbing',

        ]);
        $categoryId = $db->insertId();

        // Create projects for testing
        $db->table('projects')->insertBatch([
            [
                'title' => 'Project 1',
                'description' => 'Project 1 description',
                'status'=> 'open',
                'address' => '111 Example St.',
                'category_id' => $categoryId,
                'home_owner_id' => 2,
            ],
            [
                'title' => 'Project 2',
                'description' => 'Project 2 description',
                'status'=> 'open',
                'address' => '222 Example Dr.',
                'category_id' => $categoryId,
                'home_owner_id' => 2,
            ],
        ]);


        // Create report
        $result = $this->withSession([
            'logged_in' => true,
            'role_id' => 1,
        ])->get('/admin/reports');

        // Assertions
        $result->assertStatus(200);
        $result->assertSee('Project 1');

    }
}
