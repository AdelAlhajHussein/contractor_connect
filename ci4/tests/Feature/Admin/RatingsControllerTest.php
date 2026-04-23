<?php

namespace Tests\Feature\Admin;

use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\CIUnitTestCase;
use Faker\Factory;

final class RatingsControllerTest extends CIUnitTestCase
{
    use DatabaseTestTrait, FeatureTestTrait;

    protected $refresh = true;
    protected $namespace = 'App';
    private $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
    }

    // Helper
    private function setupRatingData(){
        $this->db->table('categories')->insert([
            'name' => $this->faker->word
        ]);
        $categoryId = $this->db->insertID();

        $contractorEmail = $this->faker->safeEmail;
        $this->db->table('users')->insertBatch([
            [
                'username' => $this->faker->userName,
                'email' => $contractorEmail,
                'first_name' => $this->faker->firstName,
                'last_name' => $this->faker->lastName,
                'role_id' => 2,
                'password_hash' => password_hash('secret', PASSWORD_DEFAULT),
            ],
            [
                'username' => $this->faker->userName,
                'email' => $this->faker->safeEmail,
                'first_name' => $this->faker->firstName,
                'last_name' => $this->faker->lastName,
                'role_id' => 3,
                'password_hash' => password_hash('secret', PASSWORD_DEFAULT),
            ]
        ]);

        $contractorId = $this->db->insertID() - 1;
        $homeownerId = $this->db->insertID();

        $projectTitle = $this->faker->sentence(3);
        $this->db->table('projects')->insert([
            'title' => $projectTitle,
            'home_owner_id' => $homeownerId,
            'category_id' => $categoryId,
            'address' => $this->faker->address
        ]);
        $projectId = $this->db->insertID();

        $this->db->table('contractor_ratings')->insert([
            'project_id' => $projectId,
            'contractor_id' => $contractorId,
            'home_owner_id' => $homeownerId,

            'quality' => 5,
            'timeliness' => 5,
            'communication' => 5,
            'pricing' => 5,

            'created_at' => date('Y-m-d H:i:s')
        ]);

        return [
            'contractor_email' => $contractorEmail,
            'project_title' => $projectTitle,
            'rating_id' => $this->db->insertID(),
            'contractor_id' => $contractorId,
            'homeowner_id' => $homeownerId,
            'project_id' => $projectId
        ];
    }


    // Tests
    public function testIndexShowsRatingsWithFilters(){

        // Set up the rating data and start the session
        $data = $this->setupRatingData();
        $session = ['logged_in' => true, 'role_id' => 1];

        // Test filter by score (5)
        $result = $this->withSession($session)->get('/admin/ratings?score=5');
        $result->assertStatus(200);
        $result->assertSee($data['contractor_email']);

        // Verify other ratings are not shown
        $resultFilteredOut = $this->withSession($session)->get('/admin/ratings?score=1');
        $resultFilteredOut->assertDontSee($data['contractor_email']);

    }

    public function testIndexSearchFiltersByText(){

        $data = $this->setupRatingData();

        $session = ['logged_in' => true, 'role_id' => 1];

        // Verify search by contractor email
        $resultEmail = $this->withSession($session)->get('/admin/ratings?q=' . urlencode($data['contractor_email']));
        $resultEmail->assertStatus(200);
        $resultEmail->assertSee($data['contractor_email']);
        $resultEmail->assertSee($data['project_title']);

        // Test search with no results
        $resultEmpty = $this->withSession($session)->get('/admin/ratings?q=NonExistentEntity');
        $resultEmpty->assertDontSee($data['contractor_email']);
    }

    public function testViewReturnsViewForValidId(){
        $data = $this->setupRatingData();
        $session = ['logged_in' => true, 'role_id' => 1];

        $result = $this->withSession($session)->get('/admin/ratings/view/' . $data['rating_id']);
        $result->assertStatus(200);

        $result->assertSee($data['contractor_email']);
        $result->assertSee($data['project_title']);
    }

    public function testViewRedirectsInvalidIds(){
        $this->setupRatingData();
        $session = ['logged_in' => true, 'role_id' => 1];

        $result = $this->withSession($session)->get('/admin/ratings/view/999');
        $result->assertRedirectTo(site_url('admin/ratings'));
    }

    public function testRemoveDeletesRatingAndRedirects(){
        $data = $this->setupRatingData();
        $session = ['logged_in' => true, 'role_id' => 1];

        $result = $this->withSession($session)->get('/admin/ratings/remove/' . $data['rating_id']);

        $result->assertRedirectTo(site_url('admin/ratings'));

        $this->dontSeeInDatabase('contractor_ratings', [
            'id' => $data['rating_id']
        ]);
    }

    public function testRemoveDoesNotDeleteRelatedProject(){
        $data = $this->setupRatingData();
        $session = ['logged_in' => true, 'role_id' => 1];

        // Attempt to delete rating
        $this->withSession($session)->get('/admin/ratings/remove/' . $data['rating_id']);

        // Verify rating is deleted
        $this->dontSeeInDatabase('contractor_ratings', ['id' => $data['rating_id']]);

        // Verify project has not been deleted
        $this->seeInDatabase('projects', ['id'=> $data['project_id']]);
    }

    public function testSuspiciousShowsFlaggedRatings(){
        $data = $this->setupRatingData();
        $session = ['logged_in' => true, 'role_id' => 1];

        $this->db->table('contractor_ratings')->insert([
            'project_id' => $data['project_id'],
            'contractor_id' => $data['contractor_id'],
            'home_owner_id' => $data['homeowner_id'],
            'quality'       => 1,
            'timeliness'    => 1,
            'communication' => 1,
            'pricing'       => 1,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $this->db->table('users')->insert([
            'username'      => $this->faker->userName,
            'email'         => $this->faker->safeEmail,
            'first_name'    => $this->faker->firstName,
            'last_name'     => $this->faker->lastName,
            'role_id'       => 3,
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT),
        ]);
        $newHomeownerId = $this->db->insertID();

        $this->db->table('contractor_ratings')->insert([
            'project_id'    => $data['project_id'],
            'contractor_id' => $data['contractor_id'],
            'home_owner_id' => $newHomeownerId,
            'quality'       => 5,
            'timeliness'    => 5,
            'communication' => 5,
            'pricing'       => 5,
            'created_at'    => date('Y-m-d H:i:s'),
        ]);

        $result = $this->withSession($session)->get('/admin/ratings/suspicious');
        $result->assertStatus(200);
        $result->assertSee($data['contractor_email']);
        $result->assertSee('2');
        $result->assertSee('3');

    }
}