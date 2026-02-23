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


$routes->get('homeowner/dashboard', 'Homeowner\Dashboard::index');
$routes->get('contractor/dashboard', 'Contractor\Dashboard::index');

$routes->get('register', 'Auth::register');


// Admin Routes
$routes->group('admin', ['filter' => 'auth'], function( $routes){
    // Dashboard
    $routes->get('/', 'Admin\DashboardController::index');
    $routes->get('dashboard', 'Admin\DashboardController::index');
    $routes->get('settings', 'Admin\DashboardController::settings');
    $routes->get('dashboard/get_table/(:any)', 'Admin\DashboardController::get_table/$1');


    // Users
    $routes->get('users', 'Admin\UsersController::index', ['filter' => 'auth']);
    $routes->get('users/toggle/(:num)', 'Admin\UsersController::toggle/$1', ['filter' => 'auth']);
    $routes->post('users/role/(:num)', 'Admin\UsersController::updateRole/$1', ['filter' => 'auth']);

    // Contractors
    $routes->get('admin/contractors', 'Admin\ContractorsController::index', ['filter' => 'auth']);
    $routes->get('admin/contractors/toggle/(:num)', 'Admin\ContractorsController::toggle/$1', ['filter' => 'auth']);
    $routes->get('admin/contractors/approve/(:num)', 'Admin\ContractorsController::approve/$1', ['filter' => 'auth']);
    $routes->get('admin/contractors/reject/(:num)', 'Admin\ContractorsController::reject/$1', ['filter' => 'auth']);


    // Homeowners
    $routes->get('homeowners', 'Admin\HomeownersController::index');
    $routes->get('homeowners/toggle/(:num)', 'Admin\HomeownersController::toggle/$1', ['filter' => 'auth']);


    // Projects
    $routes->get('projects', 'Admin\ProjectsController::index');
    $routes->get('projects/view/(:num)', 'Admin\ProjectsController::view/$1', ['filter' => 'auth']);
    $routes->get('projects/cancel/(:num)', 'Admin\ProjectsController::cancel/$1', ['filter' => 'auth']);
    $routes->get('projects/close-bidding/(:num)', 'Admin\ProjectsController::closeBidding/$1', ['filter' => 'auth']);


    // Bids
    $routes->get('bids', 'Admin\BidsController::index');
    $routes->get('bids/view/(:num)', 'Admin\BidsController::view/$1', ['filter' => 'auth']);
    $routes->get('bids/withdraw/(:num)', 'Admin\BidsController::withdraw/$1', ['filter' => 'auth']);

    // Ratings
    $routes->get('ratings', 'Admin\RatingsController::index');
    $routes->get('ratings/view/(:num)', 'Admin\RatingsController::view/$1', ['filter' => 'auth']);
    $routes->get('ratings/remove/(:num)', 'Admin\RatingsController::remove/$1', ['filter' => 'auth']);
    $routes->get('ratings/suspicious', 'Admin\RatingsController::suspicious', ['filter' => 'auth']);

    // Categories
    $routes->get('admin/categories', 'Admin\CategoriesController::index');
    $routes->get('admin/categories/create', 'Admin\CategoriesController::create', ['filter' => 'auth']);
    $routes->post('admin/categories/store', 'Admin\CategoriesController::store', ['filter' => 'auth']);
    $routes->get('admin/categories/edit/(:num)', 'Admin\CategoriesController::edit/$1', ['filter' => 'auth']);
    $routes->post('admin/categories/update/(:num)', 'Admin\CategoriesController::update/$1', ['filter' => 'auth']);
    $routes->get('admin/categories/delete/(:num)', 'Admin\CategoriesController::delete/$1', ['filter' => 'auth']);
    $routes->get('admin/categories/toggle/(:num)', 'Admin\CategoriesController::toggle/$1', ['filter' => 'auth']);

    // Reports
    $routes->get('admin/reports', 'Admin\ReportsController::index');

    // Payments (out of scope)
    $routes->get('admin/payments', 'Admin\PaymentsController::index');



});
