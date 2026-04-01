<?php

namespace Tests\Feature\Admin;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

class BidsControllerTest extends CIUnitTestCase{

    use FeatureTestTrait;
    use DatabaseTestTrait;

    protected $refresh = true;
    protected $namespace = 'App';
    protected $migrate = true;
    public function testIndexLoadsBidsWithJoins(){
        $db = \Config\Database::connect();

        $db->table('categories')->insert([
            'name'=>'Roofing',
        ]);
        $categoryId = $db->insertId();

        // Homeowner
        $db->table('users')->insert([
            'email'=>'owner@example.com',
            'username'=>'homeowner1',
            'password_hash'=>'fake_hash',
            'first_name' => 'Home',
            'last_name' => 'Owner',
            'role_id' =>2,
            'is_active'=>1,
        ]);
        $ownerId = $db->insertId();


        // Contractor
        $db->table('users')->insert([
            'email'=>'contractor@example.com',
            'username'=>'contractor1',
            'password_hash'=>'fake_hash',
            'role_id'=>3,
            'first_name'=>'Contractor',
            'last_name'=>'User',
            'is_active'=>1,
        ]);
        $contractorId = $db->insertID();

        // Project
        $db->table('projects')->insert([
            'title'=>'Fix the roof',
            'description'=>'Description of the roof project',
            'home_owner_id'=> $ownerId,
            'category_id'=> $categoryId,
            'status'=>'open',
        ]);

        $projectId = $db->insertID();

        // Bid
        $db->table('bids')->insert([
            'project_id'=>$projectId,
            'contractor_id'=>$contractorId,
            'status'=>'submitted',
            'amount'=> 500.00,
        ]);

        // Attempt
        $result = $this->withSession(['logged_in' => true, 'role_id'=> 1])
            ->get('/admin/bids');


        // Verify status
        $result->assertStatus(200);
        $result->assertSee('Fix the roof');
    }

    public function testViewMethodCalculatesTotal(){
        $db = \Config\Database::connect();

        $db->table('users')->insert([
            'email'=>'owner@example.com',
            'username'=>'homeowner1',
            'password_hash'=>'fake_hash',
            'first_name' => 'Home',
            'last_name' => 'Owner',
            'role_id' =>2,
            'is_active'=>1,

        ]);

        $ownerId = $db->insertID();
        $contractorId = $db->insertID();

        $db->table('categories')->insert([
            'name' => 'General',
        ]);
        $categoryId = $db->insertID();

        $db->table('projects')->insert([
            'title'=>'Fix the roof',
            'description'=>'Description of the roof project',
            'home_owner_id' => $ownerId,
            'category_id'=>1,
            'status'=>'open',
        ]);

        $projectId = $db->insertID();

        $db->table('bids')->insert([
            'project_id'=>$projectId,
            'contractor_id'=>$contractorId,
            'status'=>'submitted',
            'amount'=> 500.00,
        ]);

        $bidId = $db->insertID();

        $db->table('bid_tasks')->insertBatch([
            [
                'bid_id'=>$bidId,
                'est_minutes'=> 60,
                'materials_cost'=>50.00,
                'labour_cost'=>100.00,
                'hst_cost'=> 19.50,
                'task_order'=>1,
            ],
            [
                'bid_id'=> $bidId,
                'est_minutes'=> 30,
                'materials_cost'=> 10.00,
                'labour_cost'=> 50.00,
                'hst_cost'=> 7.80,
                'task_order'=> 2,
            ]
        ]);

        $result = $this->withSession(['logged_in' => true, 'role_id'=> 1])
            ->get('/admin/bids/view/'.$bidId);

        $result->assertStatus(200);
        $result->assertSee('60.00');
    }
}