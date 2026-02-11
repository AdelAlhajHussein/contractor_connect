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


$routes->get('admin/homeowners', 'Admin\HomeownersController::index');

$routes->get('admin/projects', 'Admin\ProjectsController::index');

$routes->get('admin/bids', 'Admin\BidsController::index');

$routes->get('admin/ratings', 'Admin\RatingsController::index');

$routes->get('admin/categories', 'Admin\CategoriesController::index');

$routes->get('admin/payments', 'Admin\PaymentsController::index');

$routes->get('admin/reports', 'Admin\ReportsController::index');