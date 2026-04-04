<?php

namespace Tests\Feature\Contractor;

use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

use Tests\Support\ProjectTestCase;

class BrowseControllerTest extends ProjectTestCase
{
    use FeatureTestTrait;
    use DatabaseTestTrait;

    protected $namespace = 'App';
    protected $refresh = true;
    protected $migrate = true;

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
}
?>