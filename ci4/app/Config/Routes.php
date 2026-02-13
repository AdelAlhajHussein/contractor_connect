<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('admin/users', 'Admin\UsersController::index');

$routes->get('login', 'AuthController::loginForm');
$routes->post('login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');

$routes->get('admin/users', 'Admin\UsersController::index', ['filter' => 'auth']);

$routes->get(
    'admin/users/toggle/(:num)',
    'Admin\UsersController::toggle/$1',
    ['filter' => 'auth']
);

$routes->post('admin/users/role/(:num)', 'Admin\UsersController::updateRole/$1', ['filter' => 'auth']);



$routes->get('admin', 'Admin\DashboardController::index');
$routes->get('admin/dashboard', 'Admin\DashboardController::index');



$routes->get('admin/contractors', 'Admin\ContractorsController::index', ['filter' => 'auth']);
$routes->get('admin/contractors/toggle/(:num)', 'Admin\ContractorsController::toggle/$1', ['filter' => 'auth']);
$routes->get('admin/contractors/approve/(:num)', 'Admin\ContractorsController::approve/$1', ['filter' => 'auth']);
$routes->get('admin/contractors/reject/(:num)', 'Admin\ContractorsController::reject/$1', ['filter' => 'auth']);


$routes->get('admin/homeowners', 'Admin\HomeownersController::index');
$routes->get('admin/homeowners/toggle/(:num)', 'Admin\HomeownersController::toggle/$1', ['filter' => 'auth']);


$routes->get('admin/projects', 'Admin\ProjectsController::index');
$routes->get('admin/projects', 'Admin\ProjectsController::index', ['filter' => 'auth']);
$routes->get('admin/projects/view/(:num)', 'Admin\ProjectsController::view/$1', ['filter' => 'auth']);
$routes->get('admin/projects/cancel/(:num)', 'Admin\ProjectsController::cancel/$1', ['filter' => 'auth']);
$routes->get('admin/projects/close-bidding/(:num)', 'Admin\ProjectsController::closeBidding/$1', ['filter' => 'auth']);


$routes->get('admin/bids', 'Admin\BidsController::index');
$routes->get('admin/bids', 'Admin\BidsController::index', ['filter' => 'auth']);
$routes->get('admin/bids/view/(:num)', 'Admin\BidsController::view/$1', ['filter' => 'auth']);
$routes->get('admin/bids/withdraw/(:num)', 'Admin\BidsController::withdraw/$1', ['filter' => 'auth']);



$routes->get('admin/ratings', 'Admin\RatingsController::index');
$routes->get('admin/ratings', 'Admin\RatingsController::index', ['filter' => 'auth']);
$routes->get('admin/ratings/view/(:num)', 'Admin\RatingsController::view/$1', ['filter' => 'auth']);
$routes->get('admin/ratings/remove/(:num)', 'Admin\RatingsController::remove/$1', ['filter' => 'auth']);
$routes->get('admin/ratings/suspicious', 'Admin\RatingsController::suspicious', ['filter' => 'auth']);


$routes->get('admin/categories', 'Admin\CategoriesController::index');
$routes->get('admin/categories', 'Admin\CategoriesController::index', ['filter' => 'auth']);
$routes->get('admin/categories/create', 'Admin\CategoriesController::create', ['filter' => 'auth']);
$routes->post('admin/categories/store', 'Admin\CategoriesController::store', ['filter' => 'auth']);
$routes->get('admin/categories/edit/(:num)', 'Admin\CategoriesController::edit/$1', ['filter' => 'auth']);
$routes->post('admin/categories/update/(:num)', 'Admin\CategoriesController::update/$1', ['filter' => 'auth']);
$routes->get('admin/categories/delete/(:num)', 'Admin\CategoriesController::delete/$1', ['filter' => 'auth']);
$routes->get('admin/categories/toggle/(:num)', 'Admin\CategoriesController::toggle/$1', ['filter' => 'auth']);


$routes->get('admin/payments', 'Admin\PaymentsController::index');

$routes->get('admin/reports', 'Admin\ReportsController::index');
$routes->get('admin/reports', 'Admin\ReportsController::index', ['filter' => 'auth']);
