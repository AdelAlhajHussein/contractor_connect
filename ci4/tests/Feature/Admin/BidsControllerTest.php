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

    // Tests
    public function testIndexLoadsBidsWithJoins(){
        $db = \Config\Database::connect();

        $db->table('categories')->insert([
            'name'=>'Roofing',
        ]);
        $categoryId = $db->insertId();

        // Homeowner
        $db->table('users')->insert([
            'email'=>'ownerIndexJoins@example.com',
            'username'=>'ownerIndexJoins',
            'password_hash'=>'fake_hash',
            'first_name' => 'Homeowner',
            'last_name' => 'Index Joins',
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
            'address'=> '123 Test St.',
        ]);

        $projectId = $db->insertID();

        // Bid
        $db->table('bids')->insert([
            'project_id'=>$projectId,
            'contractor_id'=>$contractorId,
            'status'=>'submitted',
            'bid_amount' => 500.00,
            'total_cost'=> 500.00,
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

        // Create homeowner
        $db->table('users')->insert([
            'email'=>'ownerViewTotal@example.com',
            'username'=>'ownerViewTotal',
            'password_hash'=>'fake_hash',
            'first_name' => 'Owner',
            'last_name' => 'View Total',
            'role_id' =>2,
            'is_active'=>1,
        ]);

        $ownerId = $db->insertID();

        // Create contractor
        $db->table('users')->insert([
            'email'=>'contractorViewTotal@example.com',
            'username'=>'contractorViewTotal',
            'password_hash'=>'fake_hash',
            'first_name' => 'Contractor',
            'last_name' => 'View Total',
            'role_id' =>3,
            'is_active'=>1,
        ]);
        $contractorId = $db->insertID();


        $db->table('categories')->insert([
            'name' => 'General',
        ]);

        $categoryId = $db->insertID();

        $db->table('projects')->insert([
            'title'=>'Fix the roof',
            'description'=>'Description of the roof project',
            'home_owner_id' => $ownerId,
            'category_id'=>$categoryId,
            'status'=>'open',
            'address'=> '123 Test St.',
        ]);
        $projectId = $db->insertID();


        $db->table('bids')->insert([
            'project_id'=>$projectId,
            'contractor_id'=>$contractorId,
            'status'=>'submitted',
            'bid_amount'=> 500.00,
            'total_cost'=> 500.00,
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
    public function testIndexFiltersWork(){
        $result = $this->withSession(['logged_in' => true, 'role_id'=> 1])
            ->get('/admin/bids?status=submitted&q=Roofing');

        $result->assertStatus(200);
    }
    public function testViewNotFoundRedirects(){
        // Attempt to get bid with non-existent id
        $result = $this->withSession(['logged_in' => true, 'role_id'=> 1])
            ->get('/admin/bids/view/9999');

        // Verify the failed get results in a redirect
        $result->assertRedirectTo(site_url('admin/bids'));

    }
    public function testWithdrawBidNotFound()
    {
        $result = $this->withSession(['logged_in' => true, 'role_id' => 1])
            ->get('/admin/bids/withdraw/9999');

        $result->assertStatus(302);
    }
    public function testWithdrawFailsIfAlreadyAccepted() {
        $db = \Config\Database::connect();

        $db->table('categories')->insert([
            'id' => 2,
            'name' => 'T2'
        ]);

        $db->table('projects')->insert([
            'id' => 2,
            'title' => 'T2',
            'home_owner_id' => 1,
            'category_id' => 2,
            'status' => 'open'
        ]);


        $db->table('bids')->insert([
            'id' => 66,
            'project_id' => 2,
            'contractor_id' => 1,
            'status' => 'accepted',
            'bid_amount' => 100.00,
            'total_cost' => 100.00,
        ]);

        $result = $this->withSession(['logged_in' => true, 'role_id' => 1])
            ->get('/admin/bids/withdraw/66');

        $result->assertStatus(302);
    }
    public function testWithdrawBidSuccess() {
        $db = \Config\Database::connect();

        $db->table('bids')->where('id', 55)->delete();

        $db->table('bids')->insert([
            'id'            => 55,
            'project_id'    => 1,
            'contractor_id' => 1,
            'status'        => 'submitted',
            'bid_amount'    => 100.00,
            'total_cost'    => 100.00,
        ]);

        $result = $this->withSession(['logged_in' => true, 'role_id' => 1])
            ->call('get', '/admin/bids/withdraw/55');

        $result->assertStatus(302);
        $this->seeInDatabase('bids', ['id' => 55, 'status' => 'withdrawn']);    }

}