<?php

namespace Tests\Support;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

abstract class ProjectTestCase extends CIUnitTestCase{
    use FeatureTestTrait, DatabaseTestTrait;

    // Standard configuration
    protected $namespace = 'App';
    protected $refresh = true;
    protected $migrate = true;


    protected function setUp(): void
    {
        \Config\Services::reset();

        $_ENV['database.tests.DBDriver'] = 'SQLite3';
        $_SERVER['database.tests.DBDriver'] = 'SQLite3';

        parent::setUp();

        $config = new \Config\Database();
        $db = \CodeIgniter\Database\Config::connect($config->tests);
        \Config\Services::injectMock('database', $db);

        $this->faker = \Faker\Factory::create();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        \Config\Services::reset();
    }


    protected function setUpUser(array $data = []){
        $defaults = [
            'username' => 'user_' . uniqid(),
            'email' => uniqid() . '@test.com',
            'first_name' => 'First',
            'last_name' => 'Last',
            'role_id' => 3,
            'is_active' => 1,
            'password_hash' => 'fake_hash'
        ];

        return $this->db->table('users')
            ->insert(array_merge($defaults, $data));
    }
    protected function setUpCategory(array $data = []){
        $defaults = ['name' => 'General'];
        return $this->db->table('categories')
            ->insert(array_merge($defaults, $data));
    }
    protected function setUpProject(array $data = []){
        // Resolve dependencies
        $categoryId  = $data['category_id'] ?? $this->setUpCategory();
        $homeownerId = $data['home_owner_id'] ?? $this->setUpUser(['role_id' => 2]);

        $defaults = [
            'title' => 'Test Project',
            'description' => 'Test description',
            'address' => '123 Project St.',
            'status' => 'open',
            'category_id' => $categoryId,
            'home_owner_id' => $homeownerId,
        ];

        return $this->db->table('projects')
            ->insert(array_merge($defaults, $data));
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
}
