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
        // Reset the Database service for a clean slate
        Services::resetSingle('database');

        // Define the data
        $mockContractors = [[
            'id'               => 10,
            'first_name'       => 'John',
            'last_name'        => 'Contractor',
            'email'            => 'john@example.com',
            'city'             => 'Toronto',
            'province'         => 'ON',
            'approval_status'  => 'approved',
            'specialties'      => 'Plumbing, Electrical',
            'avg_rating'       => '4.50',
            'rating_count'     => 1
        ]];

        $mockSpecialties = [['id' => 1, 'name' => 'Plumbing']];

        // Create a mock result
        $mockResult = $this->createMock(\CodeIgniter\Database\BaseResult::class);
        $mockResult->method('getResultArray')
            ->willReturnOnConsecutiveCalls($mockContractors, $mockSpecialties);

        // Create a mock build
        $mockBuilder = $this->getMockBuilder(\CodeIgniter\Database\BaseBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockBuilder->method('select')->willReturnSelf();
        $mockBuilder->method('join')->willReturnSelf();
        $mockBuilder->method('where')->willReturnSelf();
        $mockBuilder->method('groupBy')->willReturnSelf();
        $mockBuilder->method('having')->willReturnSelf();
        $mockBuilder->method('orderBy')->willReturnSelf();
        $mockBuilder->method('get')->willReturn($mockResult);

        // Mock the database connection
        $mockDb = $this->getMockBuilder(\CodeIgniter\Database\BaseConnection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockDb->method('table')->willReturn($mockBuilder);

        // Inject mock data into the Service layer
        Services::injectMock('database', $mockDb);

        // Attempt the request
        $result = $this->withSession([
            'user_id'   => 1,
            'logged_in' => true,
            'role_id'   => 1
        ])->get("homeowner/browse?city=Toronto&province=ON");

        // Assertions
        $result->assertStatus(200);
        $result->assertSee('John');
        $result->assertSee('Plumbing');

        // Reset again for clean slate
        Services::reset();
    }
}