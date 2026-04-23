<?php

namespace Tests\Feature\Admin;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use Faker\Factory;

class HomeownersControllerTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $refresh   = true;
    protected $namespace = 'App';
    private $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
    }

    // Helper
    private function createHomeowner(array $userOverrides = [], array $profileOverrides = [])
    {
        $db = \Config\Database::connect();

        // Create a user
        $db->table('users')->insert(array_merge([
            'username'      => $this->faker->userName,
            'email'         => $this->faker->safeEmail,
            'first_name'    => $this->faker->firstName,
            'last_name'     => $this->faker->lastName,
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT),
            'role_id'       => 3,
            'is_active'     => 1
        ], $userOverrides));

        $userId = $db->insertID();

        // Create user profile
        $db->table('home_owner_profiles')->insert(array_merge([
            'home_owner_id' => $userId,
            'address'       => $this->faker->address,
            'city'          => $this->faker->city,
            'province'      => 'ON',
            'postal_code'   => $this->faker->postcode
        ], $profileOverrides));

        return $userId;
    }

    public function testIndexFiltersAndSearch()
    {
        $city = $this->faker->city;
        $activeUser = 'active_' . $this->faker->userName;
        $inactiveUser = 'inactive_' . $this->faker->userName;

        // Create active and inactive users
        $this->createHomeowner(
            [ 'username' => $activeUser, 'is_active' => 1],
            [ 'city' => $city]
        );
        $this->createHomeowner(
            [ 'username' => $inactiveUser, 'is_active' => 0 ],
            [ 'city' => 'Vancouver' ]);

        $session = [
            'user_id' => 999,
            'logged_in' => true,
            'role_id' => 1
        ];

        // Attempt city filter
        $resSearch = $this->withSession($session)->get('admin/homeowners?q=' . urlencode($city));
        $resSearch->assertStatus(200);
        $resSearch->assertSee($activeUser);
        $resSearch->assertDontSee($inactiveUser);

        // Attempt status filter
        $resStatus = $this->withSession($session)->get('admin/homeowners?status=0');
        $resStatus->assertSee($inactiveUser);
        $resStatus->assertDontSee($activeUser);
    }

    public function testToggleChangesStatus()
    {
        $userId = $this->createHomeowner(['is_active' => 1]);
        $session = ['user_id' => 999, 'logged_in' => true, 'role_id' => 1];

        // Attempt to change status
        $result = $this->withSession($session)->get("admin/homeowners/toggle/{$userId}");
        $result->assertRedirectTo(site_url('admin/homeowners'));

        // Verify change
        $this->seeInDatabase('users', [
            'id' => $userId,
            'is_active' => 0
        ]);
    }

    public function testToggleRedirectsWhenNotFound()
    {
        $session = ['user_id' => 999, 'logged_in' => true, 'role_id' => 1];

        $result = $this->withSession($session)->get("admin/homeowners/toggle/99999");
        $result->assertRedirectTo(site_url('admin/homeowners'));
    }
}