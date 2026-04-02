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
            [ 'name'=>'Plumbing', 'is_visible' => 1],
            [ 'name' => 'Electrical', 'is_visible' => 0 ],
        ]);

        $session = ['logged_in' => true, 'role_id' => 1];

        // Basic Load
        $res1 = $this->withSession($session)->get('/admin/categories');
        $res1->assertStatus(200);
        $res1->assertSee('Plumbing');
        $res1->assertSee('Electrical');

        // Search Filter
        $res2 = $this->withSession($session)->get('/admin/categories?q=Plumb');
        $res2->assertSee('Plumbing');
        $res2->assertDontSee('Electrical');

        // Verify Visibility (1)
        $res3 = $this->withSession($session)->get('/admin/categories?visibility=1');
        $res3->assertSee('Plumbing');
        $res3->assertDontSee('Electrical');

        // Verify Hidden Visibility (0)
        $res4 = $this->withSession($session)->get('/admin/categories?visibility=0');
        $res4->assertSee('Electrical');
        $res4->assertDontSee('Plumbing');
    }
    public function testCreateViewLoads()
    {
        $session = ['logged_in' => true, 'role_id' => 1];

        $result = $this->withSession($session)
            ->get('/admin/categories/create');

        // Verify it loads a success status
        $result->assertStatus(200);

        // Verify the view contains the expected form elements
        $result->assertSee('Create Category');
        $result->assertSeeElement('input[name="name"]');
    }

    public function testStoreCategory(){

        $session = ['logged_in' => true, 'role_id' => 1];
        $categoryData = [
            'name' => 'Landscaping'
        ];

        $result = $this->withSession($session)
            ->post('/admin/categories/store', $categoryData);

        // Verify category redirection
        $result->assertRedirectTo(site_url('admin/categories'));

        // Verify data persists
        $this->seeInDatabase('categories', [
            'name' => 'Landscaping',
            'is_visible' => 1,
        ]);
    }
}
