<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Router\RouteCollection;
use Config\Services;

/**
 * @var RouteCollection $routes
 */
$routes = Services::routes();

// -------------------------------------------------------------
// DEFAULTS
// -------------------------------------------------------------
$routes->setDefaultController('UserAuth');   // Default goes to user login
$routes->setDefaultMethod('login');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

// -------------------------------------------------------------
// ✅ USER AUTH ROUTES
// -------------------------------------------------------------
$routes->get('/', 'UserAuth::login');                      // Default user login page
$routes->get('/login', 'UserAuth::login');                 // Show login page
$routes->post('/authenticate', 'UserAuth::authenticate');  // Handle login submission
$routes->get('/logout', 'UserAuth::logout');               // Logout

// -------------------------------------------------------------
// ✅ USER DASHBOARD
// -------------------------------------------------------------
$routes->get('/user/dashboard', 'UserDashboard::index'); // (make sure you have this controller)

// -------------------------------------------------------------
// ✅ USER REGISTRATION ROUTES
// -------------------------------------------------------------
$routes->get('/register', 'Register::index');      // Show registration form
$routes->post('/register/save', 'Register::save'); // Handle registration form

// -------------------------------------------------------------
// -------------------------------------------------------------
$routes->get('/admin/login', 'AdminAuth::login');                // Admin login page
$routes->post('/admin/authenticate', 'AdminAuth::authenticate'); // Admin authentication
$routes->get('/admin/logout', 'AdminAuth::logout');              // Admin logout

// -------------------------------------------------------------
// ✅ ADMIN DASHBOARD
// -------------------------------------------------------------
   // Admin dashboard view

// -------------------------------------------------------------
// ✅ ITEM MANAGEMENT (Admin-only area)
// -------------------------------------------------------------
$routes->group('items', function($routes) {
    $routes->get('/', 'Items::index');
    $routes->get('add', 'Items::add');
    $routes->post('store', 'Items::store');
    $routes->get('edit/(:num)', 'Items::edit/$1');
    $routes->post('update/(:num)', 'Items::update/$1');
    $routes->match(['get','post'], 'delete/(:num)', 'Items::delete/$1');
    $routes->post('deletePermanent/(:num)', 'Items::deletePermanent/$1');
    $routes->post('deleteMultiple', 'Items::deleteMultiple');
    $routes->post('increaseQuantity/(:num)', 'Items::increaseQuantity/$1');
    $routes->post('decreaseQuantity/(:num)', 'Items::decreaseQuantity/$1');
    $routes->post('updateMultipleQuantity', 'Items::updateMultipleQuantity');
    $routes->get('data', 'Items::data');
    $routes->get('fetch', 'Items::fetch');
    $routes->get('logs', 'Items::logs');
    $routes->get('expired', 'Items::expired');
    $routes->get('expiringSoon', 'Items::expiringSoon');
    $routes->get('deleted', 'Items::deleted', ['as' => 'deleted_items']);
});


// ✅ ADMIN AUTH ROUTES (FIXED)
$routes->get('/admin/login', 'AdminAuth::login');
$routes->post('/admin/authenticate', 'AdminAuth::authenticate');
$routes->get('/admin/logout', 'AdminAuth::logout');

// ✅ ADMIN DASHBOARD


// Admin group with role filter
$routes->group('admin', ['filter' => 'role:admin'], function($routes){
    $routes->get('dashboard', 'Items::index');
    $routes->get('expiringSoon', 'Items::expiringSoon');
    $routes->get('deleted', 'Items::deleted');
    $routes->get('logs', 'Items::logs');
});

// User group with role filter
$routes->group('user', ['filter' => 'role:user'], function($routes){
    $routes->get('dashboard', 'UserDashboard::index');
    $routes->get('expiring-soon', 'UserDashboard::expiringSoon');
    $routes->get('deleted-items', 'UserDashboard::expiredItems');
});

$routes->get('api/expiring-data', 'Dashboard::expiringData');
