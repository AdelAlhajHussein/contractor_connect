<?php

namespace Tests\Feature\Admin;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

class ContractorsControllerTest extends CIUnitTestCase
{
    use FeatureTestTrait;
    use DatabaseTestTrait;

    protected $refresh = true;
    protected $namespace = 'App';
    protected $migrate = true;

    // Helper
    private function setUpContractors(array $userData = [], array $profileData = []){
        $userDefaults = [
            'username'=>'test_contractor',
            'email'=>'contractor@test.com',
            'first_name'=>'Test',
            'last_name'=>'Contractor',
            'role_id' => 2,
            'is_active'=>1,
            'password_hash'=>'fake_hash'
        ];

        $userId = $this->db->table('users')->insert(array_merge($userDefaults,$userData));

        $profileDefaults = [
            'contractor_id'=>$userId,
            'city'=>'Toronto',
            'province'=>'ON',
            'approval_status'=>'pending',
        ];

        $this->db->table('contractor_profiles')->insert(array_merge($profileDefaults,$profileData));

        return $userId;
    }

    // Tests
    public function testIndexShowsContractors(){

        $this->setUpContractors([
            'username' => 'user1',
            'email' => 'user1@test.com',
        ]);
        $this->setUpContractors([
            'username' => 'user2',
            'email' => 'user2@test.com',
        ]);

        $result = $this->withSession(['logged_in' => true, 'role_id' => 1])
            ->get('/admin/contractors');

        $result->assertStatus(200);
        $result->assertSee('user1');
        $result->assertSee('user2');
    }
    public function testToggleSwitchesUserStatus(){
        // Create active contractor
        $userId = $this->setUpContractors(['is_active' => 1]);
        $session = ['logged_in' => true, 'role_id' => 1];

        $result = $this->withSession($session)
            ->get("/admin/contractors/toggle/$userId");

        $result->assertRedirectTo(site_url('admin/contractors'));

        $this->seeInDatabase('users', [
            'id'=>$userId,
            'is_active'=>0,
        ]);
    }


}




