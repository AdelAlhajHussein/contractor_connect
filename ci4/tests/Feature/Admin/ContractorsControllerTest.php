<?php

namespace Tests\Feature\Admin;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;
use Faker\Factory;

class ContractorsControllerTest extends CIUnitTestCase
{
    use FeatureTestTrait;
    use DatabaseTestTrait;

    protected $refresh = true;
    protected $namespace = 'App';
    protected $migrate = true;
    private $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
    }

    // Helper
    private function setUpContractors(array $userData = [], array $profileData = []){
        $userDefaults = [
            'username'      => $this->faker->userName,
            'email'         => $this->faker->safeEmail,
            'first_name'    => $this->faker->firstName,
            'last_name'     => $this->faker->lastName,
            'role_id'       => 2,
            'is_active'     => 1,
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT)
        ];

        $userId = $this->db->table('users')->insert(array_merge($userDefaults, $userData));

        $profileDefaults = [
            'contractor_id'   => $userId,
            'city'            => $this->faker->city,
            'province'        => 'ON',
            'approval_status' => 'pending',
        ];

        $this->db->table('contractor_profiles')->insert(array_merge($profileDefaults, $profileData));

        return $userId;
    }

    // Tests
    // Index
    public function testIndexShowsContractors(){

        $user1 = $this->faker->userName;
        $user2 = $this->faker->userName;

        $this->setUpContractors([
            'username' => $user1,
            'email'    => $this->faker->safeEmail,
        ]);
        $this->setUpContractors([
            'username' => $user2,
            'email'    => $this->faker->safeEmail,
        ]);

        $result = $this->withSession(['logged_in' => true, 'role_id' => 1])
            ->get('/admin/contractors');

        $result->assertStatus(200);
        $result->assertSee($user1);
        $result->assertSee($user2);
    }

    public function testIndexFiltersByStatus()
    {
        $db = \Config\Database::connect();

        $activeName   = $this->faker->userName . '_Active';
        $inactiveName = $this->faker->userName . '_Inactive';

        // Active contractor
        $db->table('users')->insert([
            'username'      => $activeName,
            'email'         => $this->faker->safeEmail,
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT),
            'first_name'    => $this->faker->firstName,
            'last_name'     => $this->faker->lastName,
            'role_id'       => 2,
            'is_active'     => 1,
        ]);
        $activeId = $db->insertId();

        $db->table('contractor_profiles')->insert([
            'contractor_id' => $activeId,
            'city'          => $this->faker->city,
            'province'      => 'ON',
            'address'       => $this->faker->address,
            'postal_code'   => $this->faker->postcode,
        ]);

        // Inactive contractor
        $db->table('users')->insert([
            'username'      => $inactiveName,
            'email'         => $this->faker->safeEmail,
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT),
            'first_name'    => $this->faker->firstName,
            'last_name'     => $this->faker->lastName,
            'role_id'       => 2,
            'is_active'     => 0,
        ]);
        $inactiveId = $db->insertId();

        $db->table('contractor_profiles')->insert([
            'contractor_id' => $inactiveId,
            'city'          => $this->faker->city,
            'province'      => 'BC',
            'address'       => $this->faker->address,
            'postal_code'   => $this->faker->postcode,
        ]);

        $result = $this->withSession(['logged_in' => true, 'role_id' => 1])
            ->get('/admin/contractors?status=0');

        $result->assertStatus(200);
        $result->assertSee($inactiveName);
        $result->assertDontSee($activeName);
    }

    public function testIndexSearchFiltersByMultipleFields(){

        $city1 = 'Toronto_' . $this->faker->word;
        $city2 = 'Vancouver_' . $this->faker->word;
        $name1 = 'SearchName_' . $this->faker->word;

        $this->setUpContractors([ 'username' => 'user_t', 'first_name' => $name1], ['city' => $city1]);
        $this->setUpContractors(['username' => 'user_v', 'first_name' => 'OtherName'], ['city' => $city2]);

        $session = ['logged_in' => true, 'role_id' => 1];

        $resultCity = $this->withSession($session)->get('/admin/contractors?q=' . urlencode($city1));
        $resultCity->assertSee('user_t');
        $resultCity->assertDontSee('user_v');

        $resultName = $this->withSession($session)->get('/admin/contractors?q=' . urlencode($name1));
        $resultName->assertSee('user_t');
        $resultName->assertDontSee('user_v');
    }

    // Toggle
    public function testToggleSwitchesUserStatus(){
        // Create active contractor
        $userId = $this->setUpContractors(['is_active' => 1]);
        $session = ['logged_in' => true, 'role_id' => 1];

        $result = $this->withSession($session)
            ->get("/admin/contractors/toggle/$userId");

        $result->assertRedirectTo(site_url('admin/contractors'));

        $this->seeInDatabase('users', [
            'id'        => $userId,
            'is_active' => 0,
        ]);
    }

    public function testToggleRedirectsWhenUserNotFound()
    {
        $session = ['logged_in' => true, 'role_id' => 1];

        $nonExistentId = 9999;

        $result = $this->withSession($session)
            ->get("/admin/contractors/toggle/$nonExistentId");

        $result->assertRedirectTo(site_url('admin/contractors'));
    }

    // Approve
    public function testApprovePendingContractor(){
        $userId = $this->setUpContractors([], ['approval_status' => 'pending']);

        $session = ['logged_in' => true, 'role_id' => 1];

        $result = $this->withSession($session)
            ->get("/admin/contractors/approve/$userId");


        $result->assertRedirectTo(site_url('admin/contractors'));

        $this->seeInDatabase('contractor_profiles', [
            'contractor_id'   => $userId,
            'approval_status' => 'approved',
        ]);
    }

    public function testApproveInsertsProfileWhenNoneExists()
    {
        // Manually insert the user
        $userId = $this->db->table('users')->insert([
            'username'      => $this->faker->userName,
            'email'         => $this->faker->safeEmail,
            'first_name'    => $this->faker->firstName,
            'last_name'     => $this->faker->lastName,
            'role_id'       => 2,
            'is_active'     => 1,
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT),
        ]);

        $session = ['logged_in' => true, 'role_id' => 1];

        $result = $this->withSession($session)
            ->get("/admin/contractors/approve/$userId");

        $result->assertRedirectTo(site_url('admin/contractors'));

        // Verify the record was actually inserted into the profile table
        $this->seeInDatabase('contractor_profiles', [
            'contractor_id'   => $userId,
            'approval_status' => 'approved',
        ]);
    }

    // Reject
    public function testRejectPendingContractor(){

        $userId = $this->setUpContractors([], ['approval_status' => 'pending']);
        $session = ['logged_in' => true, 'role_id' => 1];

        $result = $this->withSession($session)->get("/admin/contractors/reject/$userId");

        $result->assertRedirectTo(site_url('admin/contractors'));
        $this->seeInDatabase('contractor_profiles', [
            'contractor_id'   => $userId,
            'approval_status' => 'rejected',
        ]);
    }

    public function testRejectMissingProfile()
    {
        // Manually insert new user
        $userId = $this->db->table('users')->insert([
            'username'      => $this->faker->userName,
            'email'         => $this->faker->safeEmail,
            'role_id'       => 2,
            'first_name'    => $this->faker->firstName,
            'last_name'     => $this->faker->lastName,
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT),
        ]);

        $session = ['logged_in' => true, 'role_id' => 1];

        $result = $this->withSession($session)->get("/admin/contractors/reject/$userId");

        $this->seeInDatabase('contractor_profiles', [
            'contractor_id'   => $userId,
            'approval_status' => 'rejected',
        ]);
    }
}