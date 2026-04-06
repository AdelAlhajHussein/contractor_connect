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

$routes->get('register', 'Auth::register');

// ------ 1 Admin Routes ------
$routes->group('admin', ['namespace' => 'App\Controllers\Admin', 'filter' => 'auth'], function($routes) {
    $routes->get('/', 'DashboardController::index');
    $routes->get('dashboard', 'DashboardController::index');
    $routes->get('settings', 'DashboardController::settings');
    $routes->get('dashboard/get_table/(:any)', 'DashboardController::get_table/$1');

    // Users
    $routes->get('users', 'UsersController::index');
    $routes->get('users/toggle/(:num)', 'UsersController::toggle/$1');
    $routes->post('users/role/(:num)', 'UsersController::updateRole/$1');

    // Contractors
    $routes->get('contractors', 'ContractorsController::index');
    $routes->get('contractors/toggle/(:num)', 'ContractorsController::toggle/$1');
    $routes->get('contractors/approve/(:num)', 'ContractorsController::approve/$1');
    $routes->get('contractors/reject/(:num)', 'ContractorsController::reject/$1');

    // Homeowners
    $routes->get('homeowners', 'HomeownersController::index');
    $routes->get('homeowners/toggle/(:num)', 'HomeownersController::toggle/$1');

    // Projects
    $routes->get('projects', 'ProjectsController::index');
    $routes->get('projects/view/(:num)', 'ProjectsController::view/$1');
    $routes->get('projects/cancel/(:num)', 'ProjectsController::cancel/$1');
    $routes->get('projects/close-bidding/(:num)', 'ProjectsController::closeBidding/$1');

    // Bids
    $routes->get('bids', 'BidsController::index');
    $routes->get('bids/view/(:num)', 'BidsController::view/$1');
    $routes->get('bids/withdraw/(:num)', 'BidsController::withdraw/$1');

    // Ratings
    $routes->get('ratings', 'RatingsController::index');
    $routes->get('ratings/view/(:num)', 'RatingsController::view/$1');
    $routes->get('ratings/remove/(:num)', 'RatingsController::remove/$1');
    $routes->get('ratings/suspicious', 'RatingsController::suspicious');

    // Categories
    $routes->get('categories', 'CategoriesController::index');
    $routes->get('categories/create', 'CategoriesController::create');
    $routes->post('categories/store', 'CategoriesController::store');
    $routes->get('categories/edit/(:num)', 'CategoriesController::edit/$1');
    $routes->post('categories/update/(:num)', 'CategoriesController::update/$1');
    $routes->get('categories/delete/(:num)', 'CategoriesController::delete/$1');
    $routes->get('categories/toggle/(:num)', 'CategoriesController::toggle/$1');

    // Reports
    $routes->get('reports', 'ReportsController::index');
    $routes->get('payments', 'PaymentsController::index');
});

// ------- 2 Homeowner Routes -----
$routes->group('homeowner', ['namespace' => 'App\Controllers\Homeowner', 'filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'Dashboard::index');
    $routes->get('projects', 'ProjectsController::index');
    $routes->get('projects/create', 'ProjectsController::create');
    $routes->post('projects/store', 'ProjectsController::store');
    $routes->get('projects/view/(:num)', 'ProjectsController::view/$1');
    $routes->get('bids/(:num)', 'BidsController::index/$1');
    $routes->post('bids/accept/(:num)', 'BidsController::accept/$1');
    $routes->post('bids/reject/(:num)', 'BidsController::reject/$1');
    $routes->get('browse', 'BrowseController::index');
    $routes->get('contractors/view/(:num)', 'BrowseController::view/$1');
    $routes->get('profile', 'ProfileController::index');
});

// ------ 3 Contractor Routes ------
$routes->group('contractor', ['namespace' => 'App\Controllers\Contractor', 'filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'Dashboard::index');
    $routes->get('bids', 'BidsController::index');
    $routes->get('bids/create/(:num)', 'BidsController::create/$1');
    $routes->post('bids/store/(:num)', 'BidsController::store/$1');
    $routes->get('browse', 'BrowseController::index');
    $routes->get('browse/(:num)', 'BrowseController::view/$1');
});
