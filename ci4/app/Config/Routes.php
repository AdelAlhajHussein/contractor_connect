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

$routes->get('admin', 'Admin\DashboardController::index');
$routes->get('admin/dashboard', 'Admin\DashboardController::index');



$routes->get('admin/contractors', 'Admin\ContractorsController::index', ['filter' => 'auth']);



