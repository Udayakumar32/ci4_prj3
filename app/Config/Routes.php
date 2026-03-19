<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 * 
 * 
 */

$routes->get('/', function () {
    return redirect()->to(base_url('login'));
});

$routes->get ('register', 'AuthController::register',['filter' => 'guestfilter']);
$routes->post('register', 'AuthController::store');
 
$routes->get ('login',    'AuthController::login',['filter' => 'guestfilter']);
$routes->post('login',    'AuthController::authenticate');
 
$routes->get ('logout',   'AuthController::logout');
// app/Config/Routes.php
$routes->post('logout', 'AuthController::logout');  // ← POST only
 
// Protected routes — wrap in a group that uses the AuthFilter
$routes->group('', ['filter' => 'authfilter'], function ($routes) {
    $routes->get('dashboard', 'DashboardController::index');
    // add more protected routes here...
});
$routes->post('dashboard/datatable', 'DashboardController::datatable');
$routes->post('users/update/(:num)',   'DashboardController::update/$1');
 
// Admin-only: delete a user
$routes->post('users/delete/(:num)',   'DashboardController::delete/$1');
 
// Optional: server-side CSV export (admin only)
$routes->get('users/export/csv',       'DashboardController::exportCSV');