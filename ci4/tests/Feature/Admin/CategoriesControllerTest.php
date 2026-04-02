<?php

namespace Tests\Feature\Admin;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

class CategoriesControllerTest extends CIUnitTestCase
{
    use FeatureTestTrait;
    use DatabaseTestTrait;

    protected $refresh = true;
    protected $namespace = 'App';
    protected $migrate = true;

    // Tests
    public function testIndexShowsCategoriesAndFilters(){

        // Create categories
        $this->db->table('categories')->insertBatch([
            [ 'name'=>'Plumbing'],
            [ 'name' => 'Electrical' ],
        ]);

        // Attempt to load page
        $result = $this->withSession(['logged_in' => true, 'role_id' => 1])
            ->get('/admin/categories');

        // Verify basic loading
        $result->assertStatus(200);
        $result->assertSee('Plumbing');
        $result->assertSee('Electrical');

        // Test search filter
        $result = $this->get('/admin/categories?q=Plumb');
        $result->assertSee('Plumbing');
        $result->assertDontSee('Electrical');

    }
}
