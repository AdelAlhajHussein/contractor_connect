<?php

namespace Tests\Feature\Admin;

use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\CIUnitTestCase;


final class ReportsControllerTest extends CIUnitTestCase{
    use DatabaseTestTrait, FeatureTestTrait;

    protected $refresh = true;
    protected $namespace = 'App';


    public function testIndexReturnsSuccessAndCorrectData()
    {
        $db = db_connect();
        $faker = \Faker\Factory::create();

        // Create admin user
        $db->table('users')->insert([
            'email'         => $faker->email,
            'username'      => 'admin_' . $faker->userName,
            'first_name'    => 'Admin',
            'last_name'     => 'User',
            'role_id'       => 1,
            'is_active'     => 1,
            'password_hash' => password_hash($faker->password, PASSWORD_DEFAULT),
        ]);

        // Create homeowner user
        $db->table('users')->insert([
            'email'         => $faker->email,
            'username'      => 'owner_' . $faker->userName,
            'role_id'       => 2,
            'is_active'     => 1,
            'password_hash' => password_hash($faker->password, PASSWORD_DEFAULT),
        ]);
        $homeOwnerId = $db->insertID();

        // Create homeowner profile
        $db->table('home_owner_profiles')->insert([
            'home_owner_id' => $homeOwnerId,
            'address'       => $faker->streetAddress,
            'city'          => $faker->city,
            'province'      => 'ON',
            'postal_code'   => 'M1M 1M1'
        ]);

        // Create category
        $db->table('categories')->insert(['name' => 'Plumbing']);
        $categoryId = $db->insertID();

        // Create projects
        $db->table('projects')->insertBatch([
            [
                'title'         => 'Project 1',
                'description'   => $faker->paragraph,
                'status'        => 'open',
                'address'       => $faker->address,
                'category_id'   => $categoryId,
                'home_owner_id' => $homeOwnerId,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'title'         => 'Project 2',
                'description'   => $faker->paragraph,
                'status'        => 'open',
                'address'       => $faker->address,
                'category_id'   => $categoryId,
                'home_owner_id' => $homeOwnerId,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
        ]);

        // Attempt to run report
        $result = $this->withSession([
            'logged_in' => true,
            'role_id'   => 1,
            'user_id'   => 1,
        ])->get('admin/reports');

        // Assertions
        $result->assertStatus(200);
        $result->assertSee('Project 1');
        $result->assertSee('Project 2');
    }
}
