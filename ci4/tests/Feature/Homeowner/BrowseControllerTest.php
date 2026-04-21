<?php

namespace Tests\Feature\Homeowner;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use Config\Services;

class BrowseControllerTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $refresh   = true;
    protected $namespace = 'App';

    public function testIndexShowsContractorsWithFilters()
    {
        // Create homeowner user
        $homeOwnerId = $this->db->table('users')->insert([
            'username' => 'homeowner_test', 'email' => 'home@test.com',
            'first_name' => 'Eric', 'last_name' => 'Laudrum',
            'role_id' => 2, 'is_active' => 1, 'password_hash' => 'hash'
        ]);

        // Create specialty
        $this->db->table('specialties')->insert(['name' => 'Plumbing']);
        $specId = $this->db->insertID();

        // Create contractor user
        $c1 = $this->db->table('users')->insert([
            'username' => 'toronto_pro',
            'email' => 't@test.com',
            'role_id' => 3,
            'is_active' => 1,
            'first_name' => 'T',
            'last_name' => 'C',
            'password_hash' => 'h'
        ]);
        $this->db->table('contractor_profiles')->insert([
            'contractor_id' => $c1,
            'city' => 'Toronto',
            'province' => 'ON',
            'approval_status' => 'approved'
        ]);
        $this->db->table('contractor_specialties')->insert([
            'contractor_id' => $c1,
            'specialty_id' => $specId
        ]);

        // Contractor B: Vancouver, BC, no specialty
        $c2 = $this->db->table('users')->insert([
            'username' => 'vancouver_pro',
            'email' => 'v@test.com',
            'role_id' => 3,
            'is_active' => 1,
            'first_name' => 'V',
            'last_name' => 'C',
            'password_hash' => 'h'
        ]);
        $this->db->table('contractor_profiles')->insert([
            'contractor_id' => $c2, 'city' => 'Vancouver', 'province' => 'BC',
            'approval_status' => 'approved'
        ]);

        // Add a rating for Contractor A
        $this->db->table('contractor_ratings')->insert([
            'contractor_id' => $c1, 'home_owner_id' => $homeOwnerId, 'project_id' => 1,
            'quality' => 5, 'timeliness' => 5, 'communication' => 5, 'pricing' => 5
        ]);

        $session = ['user_id' => (int)$homeOwnerId, 'logged_in' => true, 'role_id' => 2];

        // Test city & province filter
        $resLoc = $this->withSession($session)->get('homeowner/browse?city=Vancouver&province=BC');
        $resLoc->assertSee('vancouver_pro');
        $resLoc->assertDontSee('toronto_pro');

        // Test specialty filter
        $resSpec = $this->withSession($session)->get("homeowner/browse?specialty_id=$specId");
        $resSpec->assertSee('toronto_pro');
        $resSpec->assertDontSee('vancouver_pro');

        // Test min rating filter
        $resRate = $this->withSession($session)->get('homeowner/browse?min_rating=4');
        $resRate->assertSee('toronto_pro');
        $resRate->assertDontSee('vancouver_pro');
    }
}