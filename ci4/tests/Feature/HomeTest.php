<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;



class HomeTest extends CIUnitTestCase{

    use FeatureTestTrait;

    public function testHomePageLoads()
    {
        $result = $this->get('/');
        $result->assertStatus(200);
    }

    public function testAboutPageLoads()
    {
        $result = $this->get('about');
        $result->assertStatus(200);
    }
}