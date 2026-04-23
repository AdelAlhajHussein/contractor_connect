<?php

namespace Tests\Feature\Admin;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;
use Faker\Factory;

class BidsControllerTest extends CIUnitTestCase
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

    // Tests
    public function testIndexLoadsBidsWithJoins()
    {
        $db = \Config\Database::connect();

        $db->table('categories')->insert([
            'name' => $this->faker->word,
        ]);
        $categoryId = $db->insertId();

        // Homeowner
        $db->table('users')->insert([
            'email' => $this->faker->safeEmail,
            'username' => $this->faker->userName,
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'role_id' => 2,
            'is_active' => 1,
        ]);
        $ownerId = $db->insertId();


        // Contractor
        $db->table('users')->insert([
            'email' => $this->faker->safeEmail,
            'username' => $this->faker->userName,
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT),
            'role_id' => 3,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'is_active' => 1,
        ]);
        $contractorId = $db->insertID();

        // Project
        $projectTitle = 'Fix the ' . $this->faker->word;
        $db->table('projects')->insert([
            'title' => $projectTitle,
            'description' => $this->faker->sentence,
            'home_owner_id' => $ownerId,
            'category_id' => $categoryId,
            'status' => 'open',
            'address' => $this->faker->address,
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
        $result->assertSee($projectTitle);
    }

    public function testViewMethodCalculatesTotal()
    {
        $db = \Config\Database::connect();

        $db->table('users')->insert([
            'email' => $this->faker->safeEmail,
            'username' => $this->faker->userName,
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'role_id' => 3,
            'is_active' => 1
        ]);
        $userId = $db->insertID();

        $db->table('categories')->insert([
            'name' => $this->faker->word
        ]);
        $catId = $db->insertID();

        $db->table('projects')->insert([
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph,
            'home_owner_id' => $userId,
            'category_id' => $catId,
            'status' => 'open',
            'address' => $this->faker->address
        ]);
        $projectId = $db->insertID();

        $db->table('bids')->insert([
            'project_id' => $projectId,
            'contractor_id'=> $userId,
            'status' => 'submitted',
            'bid_amount' => 500.00,
            'total_cost' => 500.00
        ]);
        $bidId = $db->insertID();

        $db->table('bid_tasks')->insert([
            'bid_id'  => $bidId,
            'description' => $this->faker->word,
            'est_minutes' => 60,
            'materials_cost' => 50.00,
            'labour_cost' => 100.00,
            'hst_cost' => 20.00,
            'task_order' => 1,
        ]);

        $result = $this->withSession(['logged_in' => true, 'role_id' => 1])
            ->get("/admin/bids/view/$bidId");

        $result->assertStatus(200);
        $result->assertSee('170.00');
    }

    public function testIndexFiltersWork()
    {
        $db = \Config\Database::connect();

        // Create category
        $categoryName = $this->faker->word;
        $db->table('categories')->insert(['name' => $categoryName]);
        $catId = $db->insertID();

        // Create contractor user
        $db->table('users')->insert([
            'username' => $this->faker->userName,
            'email'    => $this->faker->safeEmail,
            'role_id'  => 3,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT)
        ]);
        $conId = $db->insertID();

        // Create a project
        $projectTitle = 'Fix the ' . $categoryName;
        $db->table('projects')->insert([
            'title'         => $projectTitle,
            'category_id'   => $catId,
            'home_owner_id' => $conId,
            'status'        => 'open',
            'address'       => $this->faker->address
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
        $otherTitle = 'Other ' . $this->faker->word;
        $db->table('projects')->insert([
            'title'         => $otherTitle,
            'category_id'   => $catId,
            'home_owner_id' => $conId,
            'status'        => 'open',
            'address'       => $this->faker->address
        ]);

        // Attempt to get index with filters
        $result = $this->withSession(['logged_in' => true, 'role_id' => 1])
            ->get("/admin/bids?status=submitted&q=" . urlencode($categoryName));

        // Assertions
        $result->assertStatus(200);
        $result->assertSee($projectTitle);
        $result->assertDontSee($otherTitle);
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
            'email'=> $this->faker->safeEmail,
            'username' => $this->faker->userName,
            'password_hash'=> password_hash('secret', PASSWORD_DEFAULT),
            'first_name' => $this->faker->firstName,
            'last_name'=> $this->faker->lastName,
            'role_id' => 2,
            'is_active' => 1,
        ]);
        $userId = $db->insertID();

        $db->table('categories')->insert([
            'name' => $this->faker->word
        ]);
        $catId = $db->insertID();

        $db->table('projects')->insert([
            'title' => $this->faker->word,
            'home_owner_id' => $userId,
            'category_id' => $catId,
            'status' => 'open',
            'address' => $this->faker->address
        ]);
        $projectId = $db->insertID();

        $db->table('bids')->insert([
            'project_id' => $projectId,
            'contractor_id' => $userId,
            'status' => 'accepted',
            'bid_amount' => 100.00,
            'total_cost' => 100.00,
        ]);
        $bidId = $db->insertID();

        $result = $this->withSession(['logged_in' => true, 'role_id' => 1])
            ->get("/admin/bids/withdraw/$bidId");

        $result->assertRedirectTo(site_url("admin/bids/$bidId"));
    }

    public function testWithdrawFailsIfAlreadyRejected()
    {
        $db = \Config\Database::connect();

        $db->table('bids')->insert([
            'project_id' => 1,
            'contractor_id' => 1,
            'status' => 'rejected',
        ]);
        $bidId = $db->insertID();

        $result = $this->withSession(['logged_in' => true, 'role_id' => 1])
            ->get("/admin/bids/withdraw/$bidId");

        $result->assertRedirectTo(site_url("admin/bids/$bidId"));
    }

    public function testWithdrawBidSuccess(){
        $db = \Config\Database::connect();

        $db->table('users')->insert([
            'email' => $this->faker->safeEmail,
            'username' => $this->faker->userName,
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'role_id' => 3,
            'is_active' => 1,
        ]);
        $userId = $db->insertID();

        $db->table('categories')->insert([
            'name' => $this->faker->word
        ]);
        $catId = $db->insertID();

        $db->table('projects')->insert([
            'title' => $this->faker->sentence(3),
            'home_owner_id' => $userId,
            'category_id'=> $catId,
            'status' => 'open',
            'description' => $this->faker->paragraph,
            'address' => $this->faker->address,
        ]);
        $projectId = $db->insertID();

        $db->table('bids')->insert([
            'project_id' => $projectId,
            'contractor_id' => $userId,
            'status' => 'submitted',
            'bid_amount' => 100.00,
            'total_cost' => 100.00,
        ]);
        $bidId = $db->insertID();

        $result = $this->withSession(['logged_in' => true, 'role_id' => 1])
            ->get("/admin/bids/withdraw/$bidId");

        $result->assertRedirectTo(site_url("admin/bids/$bidId"));
        $this->seeInDatabase('bids', ['id' => $bidId, 'status' => 'withdrawn']);
    }

    public function testViewMethodHandlesMissingTaskData()
    {
        $db = \Config\Database::connect();

        $db->table('users')->insert([
            'email' => $this->faker->safeEmail,
            'username' => $this->faker->userName,
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT),
            'first_name' => $this->faker->firstName,
            'last_name'=> $this->faker->lastName,
            'role_id'=> 3,
            'is_active'=> 1,
        ]);
        $userId = $db->insertID();

        $db->table('categories')->insert([
            'name' => $this->faker->word
        ]);
        $catId = $db->insertID();

        $db->table('projects')->insert([
            'title' => $this->faker->word,
            'home_owner_id' => $userId,
            'category_id' => $catId,
            'status' => 'open',
            'address' => $this->faker->address
        ]);
        $projectId = $db->insertID();

        $db->table('bids')->insert([
            'project_id' => $projectId,
            'contractor_id' => $userId,
            'status' => 'submitted',
            'bid_amount' => 100,
            'total_cost' => 100
        ]);
        $bidId = $db->insertID();

        $db->table('bid_tasks')->insert([
            'bid_id' => $bidId,
            'est_minutes' => 0,
            'materials_cost' => 0.00,
            'labour_cost' => 0.00,
            'hst_cost' => 0.00,
            'task_order' => 1,
        ]);

        $result = $this->withSession(['logged_in' => true, 'role_id' => 1])
            ->get("/admin/bids/view/$bidId");

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
            'name' => $this->faker->word
        ]);
        $categoryId = $this->db->insertID();


        // Create contractor
        $email = $this->faker->safeEmail;
        $this->db->table('users')->insert([
            'email' => $email,
            'username' => $this->faker->userName,
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
        ]);
        $userId = $this->db->insertID();

        // Create a project
        $projectTitle = 'Fix the ' . $this->faker->word;
        $this->db->table('projects')->insert([
            'title' => $projectTitle,
            'description' => $this->faker->sentence,
            'address' => $this->faker->address,
            'category_id'=>$categoryId,
            'home_owner_id' => $userId,
        ]);
        $projectId = $this->db->insertID();

        // Create a bid
        $this->db->table('bids')->insert([
            'project_id'    => $projectId,
            'contractor_id' => $userId,
            'status'        => 'submitted',
            'created_at'    => date('Y-m-d H:i:s'),
        ]);

        $result = $this->withSession(['logged_in' => true, 'role_id' => 1])
            ->get('/admin/bids');

        // Assertions
        $result->assertStatus(200);
        $result->assertSee($projectTitle);
        $result->assertSee($email);
    }

    public function testIndexWithEmptySearchString()
    {
        $result = $this->withSession(['logged_in' => true, 'role_id' => 1])
            ->get('/admin/bids?q=');

        $result->assertStatus(200);
    }

}