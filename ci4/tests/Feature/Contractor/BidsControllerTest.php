<?php

namespace Tests\Feature\Contractor;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

use Tests\Support\ProjectTestCase;

class BidsControllerTest extends ProjectTestCase
{
    use FeatureTestTrait;
    use DatabaseTestTrait;

    protected $refresh   = true;
    protected $namespace = 'App';
    protected $migrate   = true;

    // Tests
    public function testIndexShowsOnlyContractorsBids()
    {
        // Create contractors and project
        $contractorId = $this->setUpUser(['username' => 'my_account']);
        $projectId    = $this->setUpProject(['title' => 'Fix Roof']);

        // Place competing bids
        $this->db->table('bids')->insert([
            'project_id' => $projectId,
            'contractor_id' => $contractorId,
            'bid_amount' => 100.00,
        ]);

        $result = $this->withSession([
            'logged_in' => true,
            'user_id'   => $contractorId
        ])->get('contractor/bids');

        $result->assertStatus(200);
        $result->assertSee('Fix Roof');
    }
}
