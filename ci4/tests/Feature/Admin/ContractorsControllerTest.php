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
    private function createContractor(array $userData = [], array $profileData = []){
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


}




