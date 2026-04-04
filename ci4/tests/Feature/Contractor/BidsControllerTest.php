<?php

namespace Tests\Feature\Contractor;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

class BidsControllerTest extends CIUnitTestCase
{
    use FeatureTestTrait;
    use DatabaseTestTrait;

    protected $refresh   = true;
    protected $namespace = 'App';
    protected $migrate   = true;

    // Helper functions
    private function setUpCategory(){
        $defaults = [
            'name'=> 'General'
        ];
        $id = $this->db->table('categories')->insert(array_merge($defaults, ['id'=>1]));
        return $id;
    }
    private function setUpUser(array $data = []){
        $defaults = [
            'username'=>'test_user_' . uniqid(),
            'email'=> uniqid() . '@test.com',
            'first_name'=>'First',
            'last_name'=>'Last',
            'role_id'=> 3,
            'is_active'=> 1,
            'password_hash'=> 'fake_hash'
        ];
        return $this->db->table('users')->insert(array_merge($defaults, $data));
    }
    private function setUpProject(array $data = []){

        $categoryId = $data['category_id'] ?? $this->setUpCategory();
        $homeownerId = $data['homeowner_id'] ?? $this->setUpUser(['role_id' => 2]);

        $defaults =[
            'title'=>'Test Project',
            'description'=>'Test project description',
            'address'=>'123 Project St.',
            'status'=> 'open',
            'category_id'=> $categoryId,
            'home_owner_id'=>$homeownerId,
        ];

        return $this->db->table('projects')
            ->insert(array_merge($defaults, $data));
    }
    // Tests

    public function testIndexShowsOnlyContractorsBids()
    {
        // Create contractors and project
        $contractorId = $this->setUpUser(['username' => 'my_account']);
        $otherId = $this->setUpUser(['username' => 'other_guy', 'email' => 'o@t.com']);
        $projectId = $this->setUpProject(['title' => 'Fix Roof']);

        // Place competing bids
        $this->db->table('bids')->insert([
            'project_id' => $projectId,
            'contractor_id' => $contractorId,
            'bid_amount' => 100.00,
            'status' => 'submitted'
        ]);

        $this->db->table('bids')->insert([
            'project_id' => $projectId,
            'contractor_id' => $otherId,
            'bid_amount' => 500.00
        ]);

        // Login as the first contractor
        $result = $this->withSession([
            'logged_in' => true,
            'user_id'   => $contractorId
        ])->get('contractor/bids');

        // Assert
        $result->assertStatus(200);
        $result->assertSee('Fix Roof');
        $result->assertSee('100.00');
        $result->assertDontSee('500.00');
    }
}
