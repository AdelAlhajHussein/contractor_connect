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


    public function testIndexShowsRatingsWithFilters(){

        $session = ['logged_in' => true, 'role_id' => 1];

        // Create category
        $this->db->table('categories')->insert([
            'id'=> 1,
            'name' => 'General',
        ]);


        // Create users
        $this->db->table('users')->insertBatch([
            [
                'id' => 10,
                'username' => 'Contessa',
                'email' => 'contractor@mail.com',
                'first_name'=>'Contractor',
                'last_name'=>'User',
                'role_id'=> 2,
                'password_hash' => password_hash('1234', PASSWORD_DEFAULT),
            ],
            [
                'id' => 20,
                'username' => 'Homer',
                'email' => 'homeowner@mail.com',
                'first_name'=>'Home',
                'last_name'=>'User',
                'role_id' => 3,
                'password_hash'=> password_hash('4321', PASSWORD_DEFAULT),
            ],
        ]);

        // Create a project
        $this->db->table('projects')->insert([
            'id' => 1,
            'title' => 'Kitchen Remodel',
            'home_owner_id'=> 20,
            'category_id' => 1,
            'address' => '123 Test St.',
        ]);

        // Create a rating
        $this->db->table('contractor_ratings')->insert([
            'project_id' => 1,
            'contractor_id' => 10,
            'home_owner_id' => 20,
            'quality' => 5,
            'timeliness' => 5,
            'communication' => 5,
            'pricing'=> 5,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // Test basic load
        $result = $this->withSession($session)->get('/admin/ratings');
        $result->assertStatus(200);
        $result->assertSee('contractor@mail.com');
        $result->assertSee('Kitchen Remodel');

        // Find 5* ratings
        $resultFilter = $this->withSession($session)->get('/admin/ratings?score=5');
        $resultFilter->assertSee('contractor@mail.com');

        // Verify other ratings are not shown
        $resultFilteredOut = $this->withSession($session)->get('/admin/ratings?score=1');
        $resultFilteredOut->assertDontSee('contractor@mail.com');

    }

}