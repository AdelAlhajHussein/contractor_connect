<?php

namespace Tests\Feature\Admin;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use Faker\Factory;

class CategoriesControllerTest extends CIUnitTestCase {

    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $refresh = true;
    protected $namespace = 'App';
    private $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
    }

    // Tests
    public function testIndexShowsCategoriesAndFilters(){

        $visibleName = $this->faker->unique()->word . ' Plumbing';
        $hiddenName  = $this->faker->unique()->word . ' Electrical';

        // Create categories
        $this->db->table('categories')->insertBatch([
            [ 'name' => $visibleName, 'is_visible' => 1],
            [ 'name' => $hiddenName,  'is_visible' => 0 ],
        ]);

        $session = ['logged_in' => true, 'role_id' => 1];

        // Basic Load
        $res1 = $this->withSession($session)->get('/admin/categories');
        $res1->assertStatus(200);
        $res1->assertSee($visibleName);
        $res1->assertSee($hiddenName);

        // Search Filter
        $res2 = $this->withSession($session)->get('/admin/categories?q=' . substr($visibleName, 0, 5));
        $res2->assertSee($visibleName);
        $res2->assertDontSee($hiddenName);

        // Verify visibility - 1
        $res3 = $this->withSession($session)->get('/admin/categories?visibility=1');
        $res3->assertSee($visibleName);
        $res3->assertDontSee($hiddenName);

        // Verify hidden visibility - 0
        $res4 = $this->withSession($session)->get('/admin/categories?visibility=0');
        $res4->assertSee($hiddenName);
        $res4->assertDontSee($visibleName);
    }

    public function testCreateViewLoads()
    {
        $session = ['logged_in' => true, 'role_id' => 1];
        $uri = "admin/categories/create";

        $result = $this->withSession($session)->get($uri);

        // Verify it loads a success status
        $result->assertStatus(200);

        $html = $result->getResponseBody();

        // Verify view contains the expected form elements
        $result->assertStringContainsString('Category', $html, "Category not found in page");
        $result->assertStringContainsString('name="name"', $html, "name=name not found");
    }

    public function testStoreCategory(){

        $session = ['logged_in' => true, 'role_id' => 1];
        $name = $this->faker->word . 'ing';
        $categoryData = [
            'name' => $name
        ];

        $result = $this->withSession($session)
            ->post('/admin/categories/store', $categoryData);

        // Verify category redirection
        $result->assertRedirectTo(site_url('admin/categories'));

        // Verify data persists
        $this->seeInDatabase('categories', [
            'name' => $name,
            'is_visible' => 1,
        ]);
    }

    public function testEditViewLoadsData()
    {
        $name = $this->faker->word . ' Carpentry';
        $categoryModel = model(\App\Models\CategoryModel::class);
        $categoryId = $categoryModel->insert([
            'name'       => $name,
            'is_visible' => 1,
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
            $name,
            $body,
            "$name not found in the HTML response."
        );
    }

    public function testUpdateCategory(){
        $oldName = 'Old ' . $this->faker->word;
        $newName = 'New ' . $this->faker->word;

        // Create category
        $this->db->table('categories')->insert([
            'name' => $oldName,
            'is_visible' => 1,
        ]);
        $id = $this->db->insertID();

        $session = ['logged_in' => true, 'role_id' => 1];

        // Value to update
        $updateData = [ 'name' => $newName ];

        // Attempt to update the category
        $result = $this->withSession($session)
            ->post("/admin/categories/update/{$id}", $updateData);

        // Verify redirection and correct data return
        $result->assertRedirectTo(site_url('admin/categories'));
        $this->seeInDatabase('categories', [
            'id' => $id,
            'name' => $newName,
            'is_visible' => 1,
        ]);
        $this->dontSeeInDatabase('categories', [
            'id' => $id,
            'name' => $oldName,
            'is_visible' => 1,
        ]);

    }

    public function testDeleteCategory(){
        // Create category to delete
        $this->db->table('categories')->insert([
            'name' => 'Delete ' . $this->faker->word,
            'is_visible' => 1,
        ]);
        $id = $this->db->insertID();

        // Start the session
        $session = ['logged_in' => true, 'role_id' => 1];

        // Attempt to delete the category
        $result = $this->withSession($session)
            ->get("/admin/categories/delete/{$id}");

        $result->assertRedirectTo(site_url('admin/categories'));

        $this->dontSeeInDatabase('categories', [ 'id' => $id ]);
    }

    public function testToggleCategoryVisibility(){
        // Create a category to hide
        $this->db->table('categories')->insert([
            'name' => 'Toggle ' . $this->faker->word,
            'is_visible' => 1,
        ]);
        $id = $this->db->insertID();

        // Start a session
        $session = ['logged_in' => true, 'role_id' => 1];

        // Attempt to view category
        $result = $this->withSession($session)
            ->get("/admin/categories/toggle/{$id}");

        $result->assertRedirectTo(site_url('admin/categories'));

        $this->seeInDatabase('categories', [
            'id' => $id,
            'is_visible' => 0,
        ]);
    }
}