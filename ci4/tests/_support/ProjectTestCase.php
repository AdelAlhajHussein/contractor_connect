<?php

namespace Tests\Support;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;
use Faker\Factory;
use Faker\Generator;

abstract class ProjectTestCase extends CIUnitTestCase{
    use FeatureTestTrait, DatabaseTestTrait;

    // Standard configuration
    protected $namespace = 'App';
    protected $refresh = true;
    protected $migrate = true;
    protected $faker;


    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = \Faker\Factory::create();
    }


    protected function setUpUser(array $overrides = []): int {
        $data = array_merge([
            'username' => $this->faker->userName,
            'email' => $this->faker->email,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'role_id' => 2, // Default homeowner
            'is_active' => 1,
            'password_hash' => password_hash('secret123', PASSWORD_BCRYPT),
            'created_at' => date('Y-m-d H:i:s'),
        ], $overrides);

        $this->db->table('users')->insert($data);

        return $this->db->insertID();
    }

    protected function setUpHomeownerProfile(array $overrides = []): int {
        $data = array_merge([
            'home_owner_id' => null,
            'address' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'province' => $this->faker->stateAbbr,
            'postal_code' => substr($this->faker->postcode, 0, 7),
            'created_at' => date('Y-m-d H:i:s'),
        ], $overrides);

        $this->db->table('home_owner_profiles')->insert($data);

        return $this->db->insertID();
    }


    protected function setUpCategory(array $overrides = []): int{
        $data = array_merge([
            'name' => $this->faker->jobTitle,
            'is_visible' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ], $overrides);

        $this->db->table('categories')->insert($data);

        return $this->db->insertID();
    }
    protected function setUpProject(array $overrides = []): int{
        // Resolve dependencies
        $categoryId  = $overrides['category_id']  ?? $this->setUpCategory();
        $homeownerId = $overrides['home_owner_id'] ?? $this->setUpUser(['role_id' => 2]);

        $data = array_merge([
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph,
            'address' => $this->faker->address,
            'status' => 'open',
            'category_id' => $categoryId,
            'home_owner_id' => $homeownerId,
            'created_at' => date('Y-m-d H:i:s'),
        ], $overrides);

        $this->db->table('projects')->insert($data);

        return $this->db->insertID();
    }


    protected function getInitializedController(string $controllerClass, $request = null)
    {
        $controller = new $controllerClass();
        $controller->initController(
            $request ?? \Config\Services::request(),
            \Config\Services::response(),
            \Config\Services::logger()
        );
        return $controller;
    }


    protected function tearDown(): void
    {
        parent::tearDown();
       // \Config\Services::reset();
    }
}
