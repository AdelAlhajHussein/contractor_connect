<?php

namespace Tests\Feature\Homeowner;

use Tests\Support\ProjectTestCase;
use App\Controllers\Homeowner\ProjectsController;

class ProjectsControllerTest extends ProjectTestCase{

   public function testIndexShowsHomeownerProjects(){

       // Set up homeowner user and their project
       $homeownerId = $this->setUpUser();

       $this->db->table('projects')->insert([
           'home_owner_id' => $homeownerId,
           'category_id' => 1,
           'title' => 'Homeowner Project',
           'description' => 'This project should be visible',
           'status'=> 'bidding_open'
       ]);

       // This is another user's project (should not be visible)
       $this->db->table('projects')->insert([
           'home_owner_id' => 999,
           'category_id' => 2,
           'title' => 'Other User\'s Project',
           'description' => 'This is a project from another user',
           'status'=> 'bidding_open'
       ]);

       $controller = $this->getInitializedController();
       session()->set(['user_id' => $homeownerId, 'logged_in' => true]);

       $response = $controller->index();
       $output = (string)$response;

       // Assertions
       $this->assertStringContainsString('Homeowner Project', $output);
       $this->assertStringNotContainsString('Other User\'s Project', $output);
   }
}