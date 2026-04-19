<?php

namespace Tests\Support;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;
use Faker\Factory;
use Faker\Generator;

abstract class ProjectTestCase extends CIUnitTestCase{
    use FeatureTestTrait;//, DatabaseTestTrait;

    // Standard configuration
    protected $namespace = 'App';
    protected $refresh = true;
    protected $migrate = true;
    protected $DBGroup = 'tests';
    protected $faker;


    protected function setUp(): void
    {
        $this->forceTestDatabaseConfig();
        parent::setUp();

        $this->loadDependencies();

        // 4. Ensure migrations run (since we aren't using the trait)
        $this->migrations->latest();

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

        // Create table if none exist
        $this->db->query("CREATE TABLE IF NOT EXISTS home_owner_profiles AS SELECT * FROM homeowner_profiles WHERE 1=0");

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


    public function loadDependencies()
    {
        if ($this->db === null) {
            $this->db = \Config\Database::connect($this->DBGroup ?? 'tests');
            $this->db->initialize();
        }

        if ($this->migrations === null) {
            $config = new \Config\Migrations();
            $config->enabled = true;
            $this->migrations = service('migrations', $config, $this->db, false);
            $this->migrations->setSilent(false);
        }

        if ($this->seeder === null) {
            // Manually instantiate the Seeder
            $this->seeder = new \CodeIgniter\Database\Seeder(config('Database'), $this->db);

            // Set the DBGroup so forge doesn't use default
            $rpGroup = new \ReflectionProperty($this->seeder, 'DBGroup');
            $rpGroup->setAccessible(true);
            $rpGroup->setValue($this->seeder, $this->DBGroup ?? 'tests');

            // Manually create the Forge and inject it to bypass the seeder
            $forge = \Config\Database::forge($this->db);
            $rpForge = new \ReflectionProperty($this->seeder, 'forge');
            $rpForge->setAccessible(true);
            $rpForge->setValue($this->seeder, $forge);

            $this->seeder->setSilent(true);
        }
    }

    /**
     * Overriding the trait's seeder method to force-inject the correct forge/connection.
     */
    protected static function seeder(string $group = ''): \CodeIgniter\Database\Seeder
    {
        $group = $group ?: 'tests';
        $config = config('Database');
        $db = \Config\Database::connect($group);

        $seeder = new \CodeIgniter\Database\Seeder($config, $db);

        // Force the DBGroup property
        $rpGroup = new \ReflectionProperty($seeder, 'DBGroup');
        $rpGroup->setAccessible(true);
        $rpGroup->setValue($seeder, $group);

        // Pre-generate and inject the Forge to prevent Seeder from calling forge(null)
        $forge = \Config\Database::forge($db);
        $rpForge = new \ReflectionProperty($seeder, 'forge');
        $rpForge->setAccessible(true);
        $rpForge->setValue($seeder, $forge);

        return $seeder;
    }

    /**
     * Force the Database Service to always use 'tests' and have the correct driver.
     * This fixes the "null given" error by ensuring the config is never empty.
     */
    protected function forceTestDatabaseConfig()
    {
        $config = config('Database');

        // Force the properties directly on the shared config instance
        $config->defaultGroup = 'tests';
        $config->tests = [
            'DSN'         => '',
            'hostname'    => '127.0.0.1',
            'username'    => '',
            'password'    => '',
            'database'    => ':memory:',
            'DBDriver'    => 'SQLite3',
            'DBPrefix'    => '',
            'pConnect'    => false,
            'DBDebug'     => true,
            'charset'     => 'utf8',
            'DBCollat'    => '',
            'swapPre'     => '',
            'encrypt'     => false,
            'compress'    => false,
            'strictOn'    => false,
            'failover'    => [],
            'port'        => 3306,
            'foreignKeys' => true,
            'busyTimeout' => 1000,
            'synchronous' => null,
            'dateFormat'  => [
                'date'     => 'Y-m-d',
                'datetime' => 'Y-m-d H:i:s',
                'time'     => 'H:i:s',
            ],
        ];

        // Inject this "fixed" config into the Services so the whole system uses it
        \Config\Services::injectMock('database', \Config\Database::connect('tests'));
    }


    protected function tearDown(): void
    {
        parent::tearDown();
       // \Config\Services::reset();
    }
}
