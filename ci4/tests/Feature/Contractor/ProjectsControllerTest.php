<?php

namespace Tests\Feature\Contractor;

use Tests\Support\ProjectTestCase;
use App\Controllers\Contractor\ProjectsController;

class ProjectsControllerTest extends ProjectTestCase
{
    public function testIndexShowsContractorProjects()
    {
        // Set up data
        $contractorId = $this->setUpUser();

        $this->db->table('projects')->insert([
            'home_owner_id' => 1,
            'category_id' => 1,
            'title' => 'My Bid Project',
            'description'   => 'Test description',
            'address'=>'123 Test Street',
            'start_date' => '2026-05-01',
            'end_date' => '2026-06-01',
            'status' => 'bidding_open'
        ]);
        $projectId = $this->db->insertID();

        // Place bid
        $this->db->table('bids')->insert([
            'project_id' => $projectId,
            'contractor_id' => $contractorId,
            'bid_amount' => 500.00,
            'total_cost' => 500.00,
        ]);

        // Initialize Controller
        $controller = new ProjectsController();
        $controller->initController(
            \Config\Services::request(),
            \Config\Services::response(),
            \Config\Services::logger()
        );

        // Set the session context the controller expects
        session()->set(['user_id' => $contractorId, 'logged_in' => true]);

        $response = $controller->index();

        // Assertions
        $this->assertNotNull($response);
        $output = (string)$response;

        $this->assertStringContainsString('My Bid Project', $output);
        $this->assertStringContainsString('500.00', $output);
    }
}