<?php

namespace Tests\Feature\Homeowner;

use Tests\Support\ProjectTestCase;
use CodeIgniter\Test\ControllerTestTrait;
use Config\Database;
use Config\Services;
use ReflectionClass;

class BrowseControllerTest extends ProjectTestCase
{
    use ControllerTestTrait;

    public function testIndexShowsContractorsWithFilters()
    {
        $mockContractor = [
            'id' => 1,
            'first_name' => 'John',
            'last_name' => 'Contractor',
            'email' => 'john@example.com',
            'city' => 'Toronto',
            'province' => 'ON',
            'approval_status' => 'approved',
            'specialties' => 'Plumbing, Roofing',
            'avg_rating' => '4.50',
            'rating_count' => 10
        ];

        $mockSpecialty = [
            'id' => 1,
            'name' => 'Plumbing'
        ];

        $mockResult = $this->getMockBuilder('CodeIgniter\Database\MySQLi\Result')
            ->disableOriginalConstructor()
            ->getMock();

        $mockResult->method('getResultArray')
            ->willReturnOnConsecutiveCalls([$mockContractor], [$mockSpecialty]);

        $mockBuilder = $this->getMockBuilder('CodeIgniter\Database\BaseBuilder')
            ->disableOriginalConstructor()
            ->getMock();

        $mockBuilder->method('select')->willReturnSelf();
        $mockBuilder->method('join')->willReturnSelf();
        $mockBuilder->method('where')->willReturnSelf();
        $mockBuilder->method('groupBy')->willReturnSelf();
        $mockBuilder->method('having')->willReturnSelf();
        $mockBuilder->method('orderBy')->willReturnSelf();
        $mockBuilder->method('get')->willReturn($mockResult);

        $mockDb = $this->getMockBuilder('CodeIgniter\Database\BaseConnection')
            ->disableOriginalConstructor()
            ->getMock();

        $mockDb->method('table')->willReturn($mockBuilder);

        $reflection = new ReflectionClass(Database::class);
        $instances = $reflection->getProperty('instances');
        $instances->setAccessible(true);
        $instances->setValue(['tests' => $mockDb, 'default' => $mockDb]);

        Services::injectMock('database', $mockDb);

        session()->set([
            'user_id' => 123,
            'logged_in' => true,
            'role_id' => 2
        ]);

        $_GET['city'] = 'Toronto';
        $_GET['min_rating'] = '4';

        $result = $this->controller(\App\Controllers\Homeowner\BrowseController::class)
            ->execute('index');

        $this->assertTrue($result->isOK());

        $output = $result->response()->getBody();
        $this->assertStringContainsString('John', $output);
        $this->assertStringContainsString('Contractor', $output);
        $this->assertStringContainsString('Toronto', $output);
        $this->assertStringContainsString('Plumbing, Roofing', $output);
    }
}