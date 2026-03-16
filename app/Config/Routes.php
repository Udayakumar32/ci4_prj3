<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Redirect root to login
$routes->get('/', 'AuthController::login'); // Change 'Home::index' to this
$routes->get('login', 'AuthController::login');
$routes->get('register', 'AuthController::register');
$routes->post('register/store', 'AuthController::store');

$routes->post('login/auth', 'AuthController::loginAuth');
$routes->get('logout', 'AuthController::logout');
$routes->get('dashboard', 'Dashboard::index',['filter' => 'auth']); // Create this controller next