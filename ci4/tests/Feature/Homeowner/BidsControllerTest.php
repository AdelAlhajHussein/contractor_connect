<?php

namespace Tests\Feature\Homeowner;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

class BidsControllerTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $refresh   = true;
    protected $namespace = 'App';

    public function testIndexShowsHomeownerBids()
    {
        // Reset the database
        \Config\Services::resetSingle('database');

        // Prepare mock bid query
        $mockBids = [[
            'project_id'      => 10,
            'title'           => 'Fix the roof',
            'bid_amount'      => '500.00',
            'bid_id'          => 1,
            'contractor_name' => 'JohnContractor',
            'status'          => 'submitted'
        ]];

        $mockResult = $this->createMock(\CodeIgniter\Database\BaseResult::class);
        $mockResult->method('getResultArray')->willReturn($mockBids);

        // Mock the Builder
        $mockBuilder = $this->getMockBuilder(\CodeIgniter\Database\BaseBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockBuilder->method('select')->willReturnSelf();
        $mockBuilder->method('join')->willReturnSelf();
        $mockBuilder->method('where')->willReturnSelf();
        $mockBuilder->method('orderBy')->willReturnSelf();
        $mockBuilder->method('get')->willReturn($mockResult);

        // Mock the Connection
        $mockDb = $this->getMockBuilder(\CodeIgniter\Database\BaseConnection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockDb->method('table')->willReturn($mockBuilder);

        // Inject into the service layer
        \Config\Services::injectMock('database', $mockDb);

        // Attempt to access the route
        $result = $this->withSession([
            'user_id'   => 123,
            'logged_in' => true,
            'role_id'   => 2
        ])->get('homeowner/bids');

        $result->assertStatus(200);
        $result->assertSee('Fix the roof');
        $result->assertSee('JohnContractor');

        // Reset again to clean slate
        \Config\Services::reset();
    }
}