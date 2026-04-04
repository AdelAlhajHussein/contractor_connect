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

    // Index
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

    // Create
    public function testCreateShowsValidBidForm(){
        $projectId = $this->setUpProject(['title' => 'Fix Leaky Faucet']);
        $contractorId = $this->setUpUser();

        $result = $this->withSession(['logged_in' => true, 'user_id' => $contractorId])
            ->get("contractor/bids/create/$projectId");

        $result->assertStatus(200);
        $result->assertSee('Fix Leaky Faucet');
    }
    public function testCreateRedirectsWhenProjectDoesNotExist()
    {
        $contractorId = $this->setUpUser();

        $result = $this->withSession(['logged_in' => true, 'user_id' => $contractorId])
            ->get("contractor/bids/create/9999");

        // Assertions
        $result->assertRedirectTo(site_url('contractor/browse'));
        $result->assertSessionHas('error', 'Project not found');
    }

    // Store
    public function testStoreSuccessfullyInsertsBid()
    {
        $contractorId = $this->setUpUser();
        $projectId    = $this->setUpProject();

        $postData = [
            'bid_amount' => 1250.50,
            'details' => 'I have 10 years of experience with this type of work.'
        ];

        $result = $this->withSession(['logged_in' => true, 'user_id' => $contractorId])
            ->post("contractor/bids/store/$projectId", $postData);

        $result->assertRedirectTo(site_url('contractor/bids'));
        $result->assertSessionHas('success', 'Bid submitted successfully.');

        $this->seeInDatabase('bids', [
            'project_id' => $projectId,
            'contractor_id' => $contractorId,
            'bid_amount' => 1250.50,
            'details' => 'I have 10 years of experience with this type of work.',
            'total_cost' => 1250.50
        ]);
    }
}
