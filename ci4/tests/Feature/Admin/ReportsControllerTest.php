<?php

namespace Tests\Feature\Admin;

use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\CIUnitTestCase;
use Faker\Factory;

final class ReportsControllerTest extends CIUnitTestCase
{
    use DatabaseTestTrait, FeatureTestTrait;

    protected $refresh = true;
    protected $namespace = 'App';
    private $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
    }

    public function testIndexReturnsSuccessAndCorrectData()
    {
        $db = db_connect();

        // Create admin user
        $db->table('users')->insert([
            'email'         => $this->faker->safeEmail,
            'username'      => $this->faker->userName,
            'first_name'    => $this->faker->firstName,
            'last_name'     => $this->faker->lastName,
            'role_id'       => 1,
            'is_active'     => 1,
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT),
        ]);

        // Create homeowner user
        $db->table('users')->insert([
            'email'         => $this->faker->safeEmail,
            'username'      => $this->faker->userName,
            'first_name'    => $this->faker->firstName,
            'last_name'     => $this->faker->lastName,
            'role_id'       => 2,
            'is_active'     => 1,
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT),
        ]);
        $homeOwnerId = $db->insertID();

        // Create homeowner profile
        $db->table('home_owner_profiles')->insert([
            'home_owner_id' => $homeOwnerId,
            'address'       => $this->faker->streetAddress,
            'city'          => $this->faker->city,
            'province'      => 'ON',
            'postal_code'   => $this->faker->postcode
        ]);

        // Create category
        $db->table('categories')->insert(['name' => $this->faker->word]);
        $categoryId = $db->insertID();

        // Create projects
        $project1 = 'Project ' . $this->faker->word;
        $project2 = 'Project ' . $this->faker->word;

        $db->table('projects')->insertBatch([
            [
                'title'         => $project1,
                'description'   => $this->faker->paragraph,
                'status'        => 'open',
                'address'       => $this->faker->address,
                'category_id'   => $categoryId,
                'home_owner_id' => $homeOwnerId,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'title'         => $project2,
                'description'   => $this->faker->paragraph,
                'status'        => 'open',
                'address'       => $this->faker->address,
                'category_id'   => $categoryId,
                'home_owner_id' => $homeOwnerId,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
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
        $result->assertSee($project1);
        $result->assertSee($project2);
    }
}