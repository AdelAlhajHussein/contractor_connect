<?php

namespace Tests\Feature\Admin;

use CodeIgniter\Test\FeatureTestTrait;
use Tests\Support\ProjectTestCase;

class HomeownersControllerTest extends ProjectTestCase{

    use FeatureTestTrait;

    public function testIndexShowsHomeowners(){

        $userId = $this->setUpUser(['role_id' => 3]);
        $this->setUpHomeownerProfile(['home_owner_id' => $userId]);

        // Get the generated data to verify it appears in the view
        $user = $this->db->table('users')->where('id', $userId)->get()->getRow();
        $session = ['logged_in' => true, 'role_id' => 1];

        // Attempt to get page
        $result = $this->withSession($session)->get('admin/homeowners');

        $html = (string) $result->response()->getBody();
        if (empty($html)) {
            $this->fail("The rendered HTML is empty. Check if 'admin/homeowners/index' exists and has no PHP errors.");
        }

        $this->assertStringContainsString($user->username, $html);
        $this->assertStringContainsString($user->email, $html);
    }
}