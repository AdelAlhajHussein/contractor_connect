<?php

namespace Tests\Feature\Contractor;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

class BidsControllerTest extends CIUnitTestCase
{
    use FeatureTestTrait;
    use DatabaseTestTrait;


    // Helper functions
    private function setUpProject(array $data = []){
        $defaults =[
            'title'=>'Test Project',
            'status'=>'open',
            'budget'=>500.00
        ];

        return $this->db->table('projects')
            ->insert(array_merge($defaults, $data));
    }

    public function setUpContractor(array $data = []){
        $defaults = [
            'username'=>'contractor1',
            'email'=>'contractor1@gmail.com',
            'first_name'=>'Contractor',
            'last_name'=>'One',
            'role_id'=>3,
            'is_active'=>1,
        ];
        return $this->db->table('users')
            ->insert(array_merge($defaults, $data));
    }

    // Tests



}
