<?php

namespace Tests\Feature\Admin;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

class CategoriesControllerTest extends CIUnitTestCase {

    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $refresh = true;
    protected $namespace = 'App';

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

        // Verify visibility - 1
        $res3 = $this->withSession($session)->get('/admin/categories?visibility=1');
        $res3->assertSee('Plumbing');
        $res3->assertDontSee('Electrical');

        // Verify hidden visibility - 0
        $res4 = $this->withSession($session)->get('/admin/categories?visibility=0');
        $res4->assertSee('Electrical');
        $res4->assertDontSee('Plumbing');
    }
    public function testCreateViewLoads()
    {
        $session = ['logged_in' => true, 'role_id' => 1];
        $uri = "admin/categories/create";

        $result = $this->withSession($session)->get($uri);

        // Verify it loads a success status
        $result->assertStatus(200);

        $html = $result->getResponseBody();

        // Verify the view contains the expected form elements
        $result->assertStringContainsString('Category', $html, "Category not found in page");
        $result->assertStringContainsString('name="name"', $html, "name=name not found");
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
    public function testEditViewLoadsData()
    {
        $categoryModel = model(\App\Models\CategoryModel::class);
        $categoryId = $categoryModel->insert([
            'name'       => 'Test Category',
            'is_visible' => 1
        ]);

        // Create session
        $session = ['logged_in' => true, 'role_id' => 1];
        $uri = "admin/categories/edit/{$categoryId}";

        // Attempt to load data
        $result = $this->withSession($session)->get($uri);

        // Capture the body safely
        $body = $result->response()->getBody() ?? '';

        // Verify
        $result->assertStatus(200); // loads successfully

        $this->assertStringContainsString(
            'Carpentry',
            $body,
            "Carpentry not found in the HTML response."
        );
    }


    public function testUpdateCategory(){
        // Create category
        $this->db->table('categories')->insert([
            'id'=>321,
            'name'=>'Wood working',
            'is_visible'=>1,
        ]);

        $session = ['logged_in' => true, 'role_id' => 1];

        // Value to update
        $updateData = [
            'name' => 'Carpentry'
        ];

        // Attempt to update the category
        $result = $this->withSession($session)
            ->post('/admin/categories/update/321', $updateData);

        // Verify redirection and correct data return
        $result->assertRedirectTo(site_url('admin/categories'));
        $this->seeInDatabase('categories', [
            'id'=>321,
            'name' => 'Carpentry',
            'is_visible' => 1,
        ]);
        $this->dontSeeInDatabase('categories', [
            'id'=>321,
            'name' => 'Wood working',
            'is_visible' => 1,
        ]);

    }
    public function testDeleteCategory(){
        // Create category to delete
        $this->db->table('categories')->insert([
            'id'=>111,
            'name'=>'Wood working',
            'is_visible'=>1,
        ]);

        // Start the session
        $session = ['logged_in' => true, 'role_id' => 1];

        // Attempt to delete the category
        $result = $this->withSession($session)
            ->get('/admin/categories/delete/111');

        $result->assertRedirectTo(site_url('admin/categories'));

        $this->dontSeeInDatabase('categories', [
            'id'=>111,
        ]);
    }
    public function testToggleCategoryVisibility(){
        // Create a category to hide
        $this->db->table('categories')->insert([
            'id'=>222,
            'name'=>'Snow Removal',
            'is_visible'=>1,
        ]);
        // Start a session
        $session = ['logged_in' => true, 'role_id' => 1];

        // Attempt to view category
        $result = $this->withSession($session)
            ->get('/admin/categories/toggle/222');

        $result->assertRedirectTo(site_url('admin/categories'));

        $this->seeInDatabase('categories', [
            'id'=> 222,
            'is_visible'=> 0,
        ]);
    }
}
