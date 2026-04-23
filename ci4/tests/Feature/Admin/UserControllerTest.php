<?php

namespace Tests\Feature\Admin;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;
use Faker\Factory;

class UserControllerTest extends CIUnitTestCase{
    use FeatureTestTrait, DatabaseTestTrait;

    protected $refresh = true;
    protected $namespace = 'App';
    private $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
    }

    // ----- Index Tests -----
    public function testIndexLoadsSuccessfullyForAdmin(){

        // Create an admin account to test:
        $db = \Config\Database::connect();
        $db->table('users')->insert([
            'username' => $this->faker->userName,
            'email' => $this->faker->safeEmail,
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT),
            'role_id' => 1,
            'is_active' => 1,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
        ]);
        $adminId = $db->insertID();

        // Attempt login
        $result = $this->withSession([
            'logged_in' => true,
            'role_id' => 1,
            'user_id' => (int)$adminId,
        ])->get('/admin/users');

        // Check if it worked
        $result->assertStatus(200);
        $result->assertSee((string)$this->db->table('users')->getWhere(['id' => $adminId])->getRow()->username);
    }

    public function testIndexFiltersSearchAndRole(){
        $db = \Config\Database::connect();

        $adminName = 'admin_' . $this->faker->word;
        $homeownerName = 'homeowner_' . $this->faker->word;

        // Create two users accounts to test
        $db->table('users')->insertBatch([
            [
                'username' => $adminName,
                'email' => $this->faker->unique()->safeEmail,
                'password_hash' => password_hash('secret', PASSWORD_DEFAULT),
                'role_id' => 1,
                'is_active' => 1,
                'first_name' => $this->faker->firstName,
                'last_name' => $this->faker->lastName,
            ],
            [
                'username' => $homeownerName,
                'email' => $this->faker->unique()->safeEmail,
                'password_hash' => password_hash('secret', PASSWORD_DEFAULT),
                'role_id' => 2,
                'is_active' => 1,
                'first_name' => $this->faker->firstName,
                'last_name' => $this->faker->lastName,
            ]
        ]);

        // Test Admin can search for user
        $result = $this->withSession(['logged_in' => true, 'role_id' => 1])
            ->get('/admin/users?q=homeowner&role_id=2');

        // Assertions
        $result->assertStatus(200);
        $result->assertSee($homeownerName);
        $result->assertDontSee($adminName);
    }

    public function testIndexFiltersByStatus()
    {
        $db = db_connect();

        $activeName = 'Active_' . $this->faker->userName;
        $inactiveName = 'Inactive_' . $this->faker->userName;

        // Create active user
        $db->table('users')->insert([
            'username'      => $activeName,
            'email'         => $this->faker->unique()->safeEmail,
            'first_name'    => $this->faker->firstName,
            'last_name'     => $this->faker->lastName,
            'role_id'       => 2,
            'is_active'     => 1,
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT)
        ]);

        // Create inactive user
        $db->table('users')->insert([
            'username'      => $inactiveName,
            'email'         => $this->faker->unique()->safeEmail,
            'first_name'    => $this->faker->firstName,
            'last_name'     => $this->faker->lastName,
            'role_id'       => 2,
            'is_active'     => 0,
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT)
        ]);

        $session = ['logged_in' => true, 'role_id' => 1];

        // Attempt to filter inactive users
        $result = $this->withSession($session)->get('admin/users?status=0');

        $result->assertStatus(200);
        $result->assertSee($inactiveName);
        $result->assertDontSee($activeName);
    }


    // ----- Toggle Tests -----
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
            'username' => $this->faker->userName,
            'email' => $this->faker->safeEmail,
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT),
            'role_id' => 2,
            'is_active' => 1,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
        ]);

        $userId = $db->insertID();

        // Attempt to toggle status
        $result = $this->withSession(['logged_in' => true, 'role_id' => 1])
            ->get('/admin/users/toggle/'.$userId);

        // Assertions
        $result->assertRedirectTo(site_url('admin/users'));

        // Verify status changed to 0
        $this->seeInDatabase('users', [
            'id' => $userId,
            'is_active' => 0,
        ]);
    }


    // ----- Update Role Tests -----
    public function testUpdateRoleFailsWithInvalidRoleId(){

        $result = $this->withSession(['logged_in' => true, 'role_id' => 1])
            ->post('/admin/users/role/1', ['role_id' => 99]);

        // Verify redirect on invalid role
        $result->assertStatus(302);
        $result->assertRedirectTo(site_url('admin/users'));
    }

    public function testUpdateRoleRedirectsIfUserNotFound(){

        // Attempt to add an invalid user with a valid role
        $result = $this->withSession(['logged_in' => true, 'role_id' => 1])
            ->post('admin/users/role/999', ['role_id' => 2]);

        // Verify site redirects if role is not valid
        $result->assertStatus(302);
        $result->assertRedirectTo(site_url('admin/users'));
    }

    public function testUpdateRoleChangesUserRole(){
        $db = \Config\Database::connect();

        // Create Homeowner to test conversion to Contractor
        $db->table('users')->insert([
            'username' => $this->faker->userName,
            'email' => $this->faker->safeEmail,
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT),
            'role_id' => 2,
            'is_active' => 1,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,

        ]);
        $userId = $db->insertID();

        // Update from Homeowner to Contractor
        $result = $this->withSession(['logged_in' => true, 'role_id' => 1])
            ->post('admin/users/role/'.$userId, ['role_id' => 3]);

        $result->assertRedirectTo(site_url('admin/users'));
        $this->seeInDatabase('users', [
            'id' => $userId,
            'role_id' => 3,
        ]);
    }

}