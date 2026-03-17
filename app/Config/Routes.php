<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 * 
 * 
 */

<<<<<<< HEAD
// Redirect root to login
$routes->get('/', 'AuthController::login'); // Change 'Home::index' to this
$routes->get('login', 'AuthController::login');
$routes->get('register', 'AuthController::register');
$routes->post('register/store', 'AuthController::store');

$routes->post('login/auth', 'AuthController::loginAuth');
$routes->get('logout', 'AuthController::logout');
$routes->get('dashboard', 'Dashboard::index',['filter' => 'auth']); // Create this controller next
=======
$routes->get('/', function () {
    return redirect()->to(base_url('login'));
});

$routes->get ('register', 'AuthController::register');
$routes->post('register', 'AuthController::store');
 
$routes->get ('login',    'AuthController::login');
$routes->post('login',    'AuthController::authenticate');
 
$routes->get ('logout',   'AuthController::logout');
// app/Config/Routes.php
$routes->post('logout', 'AuthController::logout');  // ← POST only
 
// Protected routes — wrap in a group that uses the AuthFilter
$routes->group('', ['filter' => 'authfilter'], function ($routes) {
    $routes->get('dashboard', 'DashboardController::index');
    // add more protected routes here...
});
>>>>>>> 29eee12bec008c94d52abe33b8833c7de7ff61a2
