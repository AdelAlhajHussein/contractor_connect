<?php

namespace Tests\Feature\Admin;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

class BidsControllerTest extends CIUnitTestCase
{
    use FeatureTestTrait;
    use DatabaseTestTrait;

    protected $refresh = true;
    protected $namespace = 'App';
    protected $migrate = true;

    // Tests
    public function testIndexLoadsBidsWithJoins()
    {
        $db = \Config\Database::connect();

        $db->table('categories')->insert([
            'name' => 'Roofing',
        ]);
        $categoryId = $db->insertId();

        // Homeowner
        $db->table('users')->insert([
            'email' => 'ownerIndexJoins@example.com',
            'username' => 'ownerIndexJoins',
            'password_hash' => 'fake_hash',
            'first_name' => 'Homeowner',
            'last_name' => 'Index Joins',
            'role_id' => 2,
            'is_active' => 1,
        ]);
        $ownerId = $db->insertId();


        // Contractor
        $db->table('users')->insert([
            'email' => 'contractor@example.com',
            'username' => 'contractor1',
            'password_hash' => 'fake_hash',
            'role_id' => 3,
            'first_name' => 'Contractor',
            'last_name' => 'User',
            'is_active' => 1,
        ]);
        $contractorId = $db->insertID();

        // Project
        $db->table('projects')->insert([
            'title' => 'Fix the roof',
            'description' => 'Description of the roof project',
            'home_owner_id' => $ownerId,
            'category_id' => $categoryId,
            'status' => 'open',
            'address' => '123 Test St.',
        ]);

        $projectId = $db->insertID();

        // Bid
        $db->table('bids')->insert([
            'project_id' => $projectId,
            'contractor_id' => $contractorId,
            'status' => 'submitted',
            'bid_amount' => 500.00,
            'total_cost' => 500.00,
        ]);

        // Attempt
        $result = $this->withSession(['logged_in' => true, 'role_id' => 1])
            ->get('/admin/bids');


        // Verify status
        $result->assertStatus(200);
        $result->assertSee('Fix the roof');
    }
    public function testViewMethodCalculatesTotal()
    {
        $db = \Config\Database::connect();

        $db->table('users')->insert([
            'id' => 100,
            'email' => 'v@t.com',
            'username' => 'v',
            'password_hash' => 'fake_hash',
            'first_name' => 'Contractor',
            'last_name' => 'User',
            'role_id' => 3,
            'is_active' => 1
        ]);

        $db->table('categories')->insert([
            'id'   => 100,
            'name' => 'General'
        ]);

        $db->table('projects')->insert([
            'id' => 100,
            'title' => 'Test Project',
            'description' => 'Project Description',
            'home_owner_id' => 100,
            'category_id' => 100,
            'status' => 'open',
            'address' => '123 Test St.'
        ]);

        $db->table('bids')->insert([
            'id' => 100,
            'project_id' => 100,
            'contractor_id'=> 100,
            'status' => 'submitted',
            'bid_amount' => 500.00,
            'total_cost' => 500.00
        ]);

        $db->table('bid_tasks')->insert([
            'bid_id'  => 100,
            'description' => 'Test Task',
            'est_minutes' => 60,
            'materials_cost' => 50.00,
            'labour_cost' => 100.00,
            'hst_cost' => 20.00,
            'task_order' => 1,
        ]);

        $result = $this->withSession(['logged_in' => true, 'role_id' => 1])
            ->get('/admin/bids/view/100');

        $result->assertStatus(200);
        $result->assertSee('170.00');
    }
    public function testIndexFiltersWork()
    {
        $db = \Config\Database::connect();

        // Create category
        $db->table('categories')->insert(['name' => 'Roofing']);
        $catId = $db->insertID();

        // Create contractor user
        $db->table('users')->insert([
            'username' => 'contractor_1',
            'email'    => 'c1@test.com',
            'role_id'  => 3,
            'first_name' => 'C', 'last_name' => '1', 'password_hash' => 'h'
        ]);
        $conId = $db->insertID();

        // Create a project
        $db->table('projects')->insert([
            'title'         => 'Fix the roofing',
            'category_id'   => $catId,
            'home_owner_id' => $conId,
            'status'        => 'open',
            'address'       => '123 test'
        ]);
        $pId = $db->insertID();

        // Create the bid
        $db->table('bids')->insert([
            'project_id'    => $pId,
            'contractor_id' => $conId,
            'status'        => 'submitted',
            'bid_amount'    => 500,
            'total_cost'    => 500
        ]);

        // Create a project that should not be seen
        $db->table('projects')->insert([
            'title'         => 'Other Project',
            'category_id'   => $catId,
            'home_owner_id' => $conId,
            'status'        => 'open',
            'address'       => '456 test'
        ]);

        // Attempt to get index with filters
        $result = $this->withSession(['logged_in' => true, 'role_id' => 1])
            ->get('/admin/bids?status=submitted&q=Roofing');

        // Assertions
        $result->assertStatus(200);
        $result->assertSee('Fix the roofing');
        $result->assertDontSee('Other Project');
    }
    public function testViewNotFoundRedirects()
    {
        // Attempt to get bid with non-existent id
        $result = $this->withSession(['logged_in' => true, 'role_id' => 1])
            ->get('/admin/bids/view/9999');

        // Verify the failed get results in a redirect
        $result->assertRedirectTo(site_url('admin/bids'));

    }
    public function testWithdrawBidNotFound()
    {
        $result = $this->withSession(['logged_in' => true, 'role_id' => 1])
            ->get('/admin/bids/withdraw/9999');

        $result->assertRedirectTo(site_url('admin/bids'));
    }
    public function testWithdrawFailsIfAlreadyAccepted()
    {
        $db = \Config\Database::connect();

        $db->table('users')->insert([
            'id' => 1,
            'email'=> 'owner_accepted@example.com',
            'username' => 'owner_acc',
            'password_hash'=> 'fake_hash',
            'first_name' => 'Test',
            'last_name'=> 'Owner',
            'role_id' => 2,
            'is_active' => 1,
        ]);

        $db->table('categories')->insert([
            'id' => 2,
            'name' => 'T2'
        ]);

        $db->table('projects')->insert([
            'id' => 2,
            'title' => 'T2',
            'home_owner_id' => 1,
            'category_id' => 2,
            'status' => 'open',
            'address' => '789 Test Rd.'
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

        $result->assertRedirectTo(site_url('admin/bids/66'));
    }
    public function testWithdrawFailsIfAlreadyRejected()
    {
        $this->db->table('bids')->insert([
            'id' => 77,
            'project_id' => 1,
            'contractor_id' => 1,
            'status' => 'rejected',
        ]);

        $result = $this->withSession(['logged_in' => true, 'role_id' => 1])
            ->get('/admin/bids/withdraw/77');

        $result->assertRedirectTo(site_url('admin/bids/77'));
    }
    public function testWithdrawBidSuccess(){
        $db = \Config\Database::connect();

        $db->table('users')->insert([
            'id' => 10,
            'email' => 'c10@t.com',
            'username' => 'u10',
            'password_hash' => 'fake_hash',
            'first_name' => 'Contractor',
            'last_name' => 'Ten',
            'role_id' => 3,
            'is_active' => 1,
        ]);

        $db->table('categories')->insert([
            'id' => 10,
            'name' => 'T10'
        ]);

        $db->table('projects')->insert([
            'id' => 10,
            'title' => 'Test Project',
            'home_owner_id' => 10,
            'category_id'=> 10,
            'status' => 'open',
            'description' => 'Project Description',
            'address' => '123 Test St.',
        ]);

        $db->table('bids')->insert([
            'id' => 55,
            'project_id' => 10,
            'contractor_id' => 10,
            'status' => 'submitted',
            'bid_amount' => 100.00,
            'total_cost' => 100.00,
        ]);

        $result = $this->withSession(['logged_in' => true, 'role_id' => 1])
            ->get('/admin/bids/withdraw/55');

        $result->assertRedirectTo(site_url('admin/bids/55'));
        $this->seeInDatabase('bids', ['id' => 55, 'status' => 'withdrawn']);
    }
    public function testViewMethodHandlesMissingTaskData()
    {
        $db = \Config\Database::connect();

        $db->table('users')->insert([
            'id' => 20,
            'email' => 'c20@t.com',
            'username' => 'u20',
            'password_hash' => 'fake_hash',
            'first_name' => 'Contractor',
            'last_name'=> 'Twenty',
            'role_id'=> 3,
            'is_active'=> 1,
        ]);

        $db->table('categories')->insert([
            'id' => 20,
            'name' => 'T20'
        ]);

        $db->table('projects')->insert([
            'id' => 20,
            'title' => 'T',
            'home_owner_id' => 20,
            'category_id' => 20,
            'status' => 'open',
            'address' => '456 Test Ave.'
        ]);

        $db->table('bids')->insert([
            'id' => 20,
            'project_id' => 20,
            'contractor_id' => 20,
            'status' => 'submitted',
            'bid_amount' => 100,
            'total_cost' => 100
        ]);

        $db->table('bid_tasks')->insert([
            'bid_id' => 20,
            'est_minutes' => 0,
            'materials_cost' => 0.00,
            'labour_cost' => 0.00,
            'hst_cost' => 0.00,
            'task_order' => 1,
        ]);

        $result = $this->withSession(['logged_in' => true, 'role_id' => 1])
            ->get('/admin/bids/view/20');

        $result->assertStatus(200);
        $result->assertSee('0.00');
    }
    public function testFilterIgnoresInvalidStatus(){
        // Attempt to filter an invalid status
        $result = $this->withSession(['logged_in' => true, 'role_id' => 1])
            ->get('/admin/bids?status=invalid');

        // Verify function still works even if filter doesn't filter
        $result->assertStatus(200);
    }
    public function testIndexWithoutFiltersReturnsAll()
    {
        // Create a category
        $this->db->table('categories')->insert([
            'name' => 'General'
        ]);
        $categoryId = $this->db->insertID();


        // Create contractor
        $this->db->table('users')->insert([
            'email' => 'test@example.com',
            'username' => 'testuser',
            'password_hash' => 'fake_hash',
            'first_name' => 'Test',
            'last_name' => 'User',
        ]);
        $userId = $this->db->insertID();

        // Create a project
        $this->db->table('projects')->insert([
            'title' => 'Fix the roof',
            'description' => 'Project description',
            'address' => '123 Address St.',
            'category_id'=>$categoryId,
            'home_owner_id' => $userId,
        ]);
        $projectId = $this->db->insertID();

        // Create a bid
        $this->db->table('bids')->insert([
            'project_id'    => $projectId,
            'contractor_id' => $userId,
            'status'        => 'submitted',
            'created_at'    => date('Y-m-d H:i:s')
        ]);

        $result = $this->withSession(['logged_in' => true, 'role_id' => 1])
            ->get('/admin/bids');

        // Assertions
        $result->assertStatus(200);
        $result->assertSee('Fix the roof');
        $result->assertSee('test@example.com');
    }
    public function testIndexWithEmptySearchString()
    {
        $result = $this->withSession(['logged_in' => true, 'role_id' => 1])
            ->get('/admin/bids?q=');

        $result->assertStatus(200);
    }

}