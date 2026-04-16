<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');
//$routes->get('admin/users', 'Admin\UsersController::index');

//$routes->get('about', 'Home::about');
$routes->get('about', 'Home::about');
$routes->get('login', 'AuthController::loginForm');
$routes->post('login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');

$routes->post('register', 'AuthController::register');
$routes->get('register', 'AuthController::registerForm');


// ------ 1 Admin Routes ------
$routes->group('admin', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Admin\DashboardController::index');
    $routes->get('dashboard', 'Admin\DashboardController::index');
    $routes->get('settings', 'Admin\DashboardController::settings');
    $routes->get('dashboard/get_table/(:any)', 'Admin\DashboardController::get_table/$1');

    // Users
    $routes->get('users', 'Admin\UsersController::index');
    $routes->get('users/toggle/(:num)', 'Admin\UsersController::toggle/$1');
    $routes->post('users/role/(:num)', 'Admin\UsersController::updateRole/$1');

    // Contractors
    $routes->get('contractors', 'Admin\ContractorsController::index');
    $routes->get('contractors/toggle/(:num)', 'Admin\ContractorsController::toggle/$1');
    $routes->get('contractors/approve/(:num)', 'Admin\ContractorsController::approve/$1');
    $routes->get('contractors/reject/(:num)', 'Admin\ContractorsController::reject/$1');

    // Homeowners
    $routes->get('homeowners', 'Admin\HomeownersController::index');
    $routes->get('homeowners/toggle/(:num)', 'Admin\HomeownersController::toggle/$1');

    // Projects
    $routes->get('projects', 'Admin\ProjectsController::index');
    $routes->get('projects/view/(:num)', 'Admin\ProjectsController::view/$1');
    $routes->get('projects/cancel/(:num)', 'Admin\ProjectsController::cancel/$1');
    $routes->get('projects/close-bidding/(:num)', 'Admin\ProjectsController::closeBidding/$1');

    // Bids
    $routes->get('bids', 'Admin\BidsController::index');
    $routes->get('bids/view/(:num)', 'Admin\BidsController::view/$1');
    $routes->get('bids/withdraw/(:num)', 'Admin\BidsController::withdraw/$1');

    // Ratings
    $routes->get('ratings', 'Admin\RatingsController::index');
    $routes->get('ratings/view/(:num)', 'Admin\RatingsController::view/$1');
    $routes->get('ratings/remove/(:num)', 'Admin\RatingsController::remove/$1');
    $routes->get('ratings/suspicious', 'Admin\RatingsController::suspicious');

    // Categories
    $routes->get('categories', 'Admin\CategoriesController::index');
    $routes->get('categories/create', 'Admin\CategoriesController::create');
    $routes->post('categories/store', 'Admin\CategoriesController::store');
    $routes->get('categories/edit/(:num)', 'Admin\CategoriesController::edit/$1');
    $routes->post('categories/update/(:num)', 'Admin\CategoriesController::update/$1');
    $routes->get('categories/delete/(:num)', 'Admin\CategoriesController::delete/$1');
    $routes->get('categories/toggle/(:num)', 'Admin\CategoriesController::toggle/$1');

    // Reports
    $routes->get('reports', 'Admin\ReportsController::index');
    $routes->get('payments', 'Admin\PaymentsController::index');
});

// ------- 2 Homeowner Routes -----
$routes->group('homeowner', ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'Homeowner\DashboardController::index');
    $routes->get('projects', 'Homeowner\ProjectsController::index');
    $routes->get('projects/create', 'Homeowner\ProjectsController::create');
    $routes->post('projects/store', 'Homeowner\ProjectsController::store');
    $routes->get('projects/view/(:num)', 'Homeowner\ProjectsController::view/$1');
    $routes->get('bids/(:num)', 'Homeowner\BidsController::index/$1');
    $routes->post('bids/accept/(:num)', 'Homeowner\BidsController::accept/$1');
    $routes->post('bids/reject/(:num)', 'Homeowner\BidsController::reject/$1');
    $routes->get('browse', 'Homeowner\BrowseController::index');
    $routes->get('contractors/view/(:num)', 'Homeowner\BrowseController::view/$1');
    $routes->get('profile', 'Homeowner\ProfileController::index');
});

// ------ 3 Contractor Routes ------
$routes->group('contractor', ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'Contractor\DashboardController::index');
    $routes->get('bids', 'Contractor\BidsController::index');
    $routes->get('bids/create/(:num)', 'Contractor\BidsController::create/$1');
    $routes->post('bids/store/(:num)', 'Contractor\BidsController::store/$1');
    $routes->get('browse', 'Contractor\BrowseController::index');
    $routes->get('browse/(:num)', 'Contractor\BrowseController::view/$1');
});
