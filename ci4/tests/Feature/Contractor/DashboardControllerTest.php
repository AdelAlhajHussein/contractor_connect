<?php

namespace Tests\Feature\Contractor;

use Tests\Support\ProjectTestCase;
use App\Controllers\Contractor\DashboardController;

class DashboardControllerTest extends ProjectTestCase
{
    public function testIndexShowsContractorDashboard()
    {

        $userData = [
            'username'=> 'dash_tester',
            'first_name'=> 'John',
            'last_name'=> 'Contractor',
            'email' => 'john@example.com',
            'phone' => '555-0199'
        ];
        $contractorId = $this->setUpUser($userData);

        $controller = new DashboardController();
        $controller->initController(
            \Config\Services::request(),
            \Config\Services::response(),
            \Config\Services::logger()
        );

        session()->set(['user_id' => $contractorId, 'logged_in' => true]);

        $response = $controller->index();

        $this->assertNotNull($response);
        $output = (string)$response;

        $this->assertStringContainsString('dash_tester', $output);
        $this->assertStringContainsString('John', $output);
        $this->assertStringContainsString('Contractor', $output);
        $this->assertStringContainsString('john@example.com', $output);
        $this->assertStringContainsString('555-0199', $output);
    }
}