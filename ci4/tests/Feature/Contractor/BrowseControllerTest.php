<?php

namespace Tests\Feature\Contractor;

use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

use Tests\Support\ProjectTestCase;

class BrowseControllerTest extends ProjectTestCase
{
    use DatabaseTestTrait;

    protected $namespace = 'App';
    protected $refresh = true;
    protected $migrate = true;

    // Index
    public function testIndexShowsOnlyAvailableProjects(){
        $contractorId = $this->setUpUser();

        $this->setUpProject(['title'=>'Available Projects', 'status'=> 'closed']);

        $alreadyBidId = $this->setUpProject([
            'title' => 'Already Bid',
            'status' => 'bidding_open'
        ]);

        $this->db->table('bids')->insert([
            'project_id' => $alreadyBidId,
            'contractor_id' => $contractorId,
            'bid_amount' => 100
        ]);

        $result = $this->withSession(['logged_in' => true, 'user_id' => $contractorId])
            ->get('contractor/browse');

        $result->assertStatus(200);
        $result->assertSee('Available Project');
        $result->assertDontSee('Closed Project');
        $result->assertDontSee('Already Bid');
    }

    // Details
    public function testDetailsMethodCoverageDirect()
    {
        $contractorId = $this->setUpUser();
        $projectId = $this->setUpProject(['title' => 'Direct Test']);

        // Instantiate the controller
        $controller = new \App\Controllers\Contractor\BrowseController();
        $controller->initController(
            \Config\Services::request(),
            \Config\Services::response(),
            \Config\Services::logger()
        );

        session()->set(['user_id' => $contractorId, 'logged_in' => true]);

        $response = $controller->details($projectId);

        // Assertions
        $this->assertNotNull($response);
        $this->assertStringContainsString('Direct Test', (string)$response);
    }
    public function testDetailsRedirectsForInvalidProject()
    {
        $contractorId = $this->setUpUser();

        $controller = new \App\Controllers\Contractor\BrowseController();
        $controller->initController(
            \Config\Services::request(),
            \Config\Services::response(),
            \Config\Services::logger()
        );

        session()->set(['user_id' => $contractorId, 'logged_in' => true]);

        $result = $controller->details(9999);

        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $result);

        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Project not found', session('error'));
    }
}
?>