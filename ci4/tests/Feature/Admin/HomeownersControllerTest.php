<?php

namespace Tests\Feature\Admin;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

class HomeownersControllerTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $refresh   = true;
    protected $namespace = 'App';

    // Helper
    private function createHomeowner(array $userOverrides = [], array $profileOverrides = [])
    {
        $db = \Config\Database::connect();

        // Create a user
        $db->table('users')->insert(array_merge([
            'username'      => 'user_' . uniqid(),
            'email'         => uniqid() . '@test.com',
            'first_name'    => 'Test',
            'last_name'     => 'User',
            'password_hash' => 'hash',
            'role_id'       => 3,
            'is_active'     => 1
        ], $userOverrides));

        $userId = $db->insertID();

        // Create user profile
        $db->table('home_owner_profiles')->insert(array_merge([
            'home_owner_id' => $userId,
            'address'       => '123 Main St',
            'city'          => 'Toronto',
            'province'      => 'ON',
            'postal_code'   => 'M1M 1M1'
        ], $profileOverrides));

        return $userId;
    }

    public function testIndexFiltersAndSearch()
    {
        // Create active and inactive users
        $this->createHomeowner([
            'username' => 'active_eric',
            'is_active' => 1
        ], [
            'city' => 'Toronto'
        ]);
        $this->createHomeowner([
            'username' => 'inactive_adel',
            'is_active' => 0],
        [
            'city' => 'Vancouver']);

        $session = ['user_id' => 999, 'logged_in' => true, 'role_id' => 1];

        // Attempt city filter
        $resSearch = $this->withSession($session)->get('admin/homeowners?q=Toronto');
        $resSearch->assertStatus(200);
        $resSearch->assertSee('active_eric');
        $resSearch->assertDontSee('inactive_adel');

        // Attempt status filter
        $resStatus = $this->withSession($session)->get('admin/homeowners?status=0');
        $resStatus->assertSee('inactive_adel');
        $resStatus->assertDontSee('active_eric');
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