<?php

namespace Tests\Feature\Homeowner;

use Tests\Support\ProjectTestCase;
use App\Controllers\Homeowner\BidsController;

class BidsControllerTest extends ProjectTestCase
{
    public function testIndexShowsBidsForHomeownerProject()
    {
        // Set up homeowner & their project
        $homeownerId = $this->setUpUser(['username' => 'the_owner']);
        $this->db->table('projects')->insert([
            'home_owner_id'=> $homeownerId,
            'category_id' => 1,
            'title'=> 'Fix the Porch',
            'address'=> '123 Homeowner Lane',
            'status'=> 'open'
        ]);
        $projectId = $this->db->insertID();

        // Setup Contractor and a Bid
        $contractorId = $this->setUpUser(['username' => 'pro_builder']);
        $this->db->table('bids')->insert([
            'project_id' => $projectId,
            'contractor_id' => $contractorId,
            'bid_amount' => 1500.00,
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ]);

        // Initialize controller direct call
        $controller = new BidsController();
        $controller->initController(
            \Config\Services::request(),
            \Config\Services::response(),
            \Config\Services::logger()
        );

        // Homeowner Session
        session()->set(['user_id' => $homeownerId, 'logged_in' => true]);

        // Attempt to call index with the $projectId
        $response = $controller->index($projectId);

        // Assertions
        $this->assertNotNull($response);
        $output = (string)$response;

        // Verify the joined data appears in the view
        $this->assertStringContainsString('Fix the Porch', $output);
        $this->assertStringContainsString('pro_builder', $output);
        $this->assertStringContainsString('1500.00', $output);
    }
}