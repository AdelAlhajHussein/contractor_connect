<?php

namespace Tests\Feature\Admin;

use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\CIUnitTestCase;

final class RatingsControllerTest extends CIUnitTestCase
{
    use DatabaseTestTrait, FeatureTestTrait;

    protected $refresh = true;
    protected $namespace = 'App';

    // Helper
    private function setupRatingData(){
        $this->db->table('categories')->insert([
            'id' => 1,
            'name' => 'General'
        ]);

        $this->db->table('users')->insertBatch([
            [
                'id' => 10,
                'username' => 'contractor89',
                'email' => 'contractor@mail.com',
                'first_name' => 'Contractor',
                'last_name' => 'User',
                'role_id' => 2,
                'password_hash'=>'fake_hash',
            ],
            [
                'id' => 20,
                'username' => 'homeowner99',
                'email' => 'homeowner@mail.com',
                'first_name' => 'Homeowner',
                'last_name' => 'User',
                'role_id' => 3,
                'password_hash'=>'fake_hash',
            ]
        ]);

        $this->db->table('projects')->insert([
            'id' => 1,
            'title' => 'Kitchen Remodel',
            'home_owner_id' => 20,
            'category_id' => 1,
            'address' => '100 Example Ave.'
        ]);

        $this->db->table('contractor_ratings')->insert([
            'id' => 1,
            'project_id' => 1,
            'contractor_id' => 10,
            'home_owner_id' => 20,

            'quality' => 5,
            'timeliness' => 5,
            'communication' => 5,
            'pricing' => 5,

            'created_at' => date('Y-m-d H:i:s')
        ]);
    }


    // Tests
    public function testIndexShowsRatingsWithFilters(){

        // Set up the rating data and start the session
        $this->setupRatingData();
        $session = ['logged_in' => true, 'role_id' => 1];

        // Test filter by score (5)
        $result = $this->withSession($session)->get('/admin/ratings?score=5');
        $result->assertStatus(200);
        $result->assertSee('contractor@mail.com');

        // Verify other ratings are not shown
        $resultFilteredOut = $this->withSession($session)->get('/admin/ratings?score=1');
        $resultFilteredOut->assertDontSee('contractor@mail.com');

    }
    public function testIndexSearchFiltersByText(){

        $this->setupRatingData();

        $session = ['logged_in' => true, 'role_id' => 1];

        // Verify search by contractor email
        $resultEmail = $this->withSession($session)->get('/admin/ratings?q=contractor@mail.com');
        $resultEmail->assertStatus(200);
        $resultEmail->assertSee('contractor@mail.com');
        $resultEmail->assertSee('Kitchen Remodel');

        // Test search with no results
        $resultEmpty = $this->withSession($session)->get('/admin/ratings?q=NonExistentEntity');
        $resultEmpty->assertDontSee('contractor@mail.com');
    }
    public function testViewReturnsViewForValidId(){
        $this->setupRatingData();
        $session = ['logged_in' => true, 'role_id' => 1];

        $result = $this->withSession($session)->get('/admin/ratings/view/1');
        $result->assertStatus(200);

        $result->assertSee('contractor@mail.com');
        $result->assertSee('Kitchen Remodel');
    }
    public function testViewRedirectsInvalidIds(){
        $this->setupRatingData();
        $session = ['logged_in' => true, 'role_id' => 1];

        $result = $this->withSession($session)->get('/admin/ratings/view/999');
        $result->assertRedirectTo(site_url('admin/ratings'));
    }
}