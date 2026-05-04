<?php

namespace Config;

use CodeIgniter\Router\RouteCollection;
use Config\Services;

/** @var RouteCollection $routes */
$routes = Services::routes();

// -------------------------------------------------------------
// DEFAULT SETTINGS
// -------------------------------------------------------------
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Auth');
$routes->setDefaultMethod('login');
$routes->setTranslateURIDashes(true);
$routes->set404Override();
$routes->setAutoRoute(false); // Security best practice

// -------------------------------------------------------------
// AUTH ROUTES
// -------------------------------------------------------------
$routes->get('/', 'Auth::login');
$routes->get('login', 'Auth::login');
$routes->post('authenticate', 'Auth::authenticate');
$routes->get('logout', 'Auth::logout');

$routes->get('register', 'Auth::register');
$routes->post('register/save', 'Auth::save');

// -------------------------------------------------------------
// USER DASHBOARD & POS ROUTES
// -------------------------------------------------------------
$routes->group('user', function ($routes) {
    // Dashboard
    $routes->get('dashboard', 'UserDashboard::index');
    $routes->get('dashboard/expired', 'UserDashboard::expired');
    $routes->get('dashboard/expiring-soon', 'UserDashboard::expiringSoon');
    $routes->get('dashboard/deleted-items', 'UserDashboard::deleted');
    $routes->get('dashboard/logs', 'UserDashboard::logs');


    // Stock Requests
    $routes->get('dashboard/request-stock-adjustment', 'UserDashboard::requestStockAdjustment');
    $routes->post('dashboard/request-stock-adjustment', 'UserRequestController::submitStockRequest');
    $routes->post('submit-stock-request', 'UserRequestController::submitStockRequest');

    // Pull-Outs
    $routes->post('submit-pull-out', 'PullOutController::submit');

    // FAQs
    $routes->get('dashboard/faqs', 'UserDashboard::faqs');
    $routes->get('get-faqs', 'UserRequestController::getFAQs');

    // User POS
    $routes->get('pos', 'UserPosController::index');
    $routes->post('pos/sell', 'UserPosController::sell');
});

// -------------------------------------------------------------
// ADMIN ROUTES
// -------------------------------------------------------------
$routes->group('admin', function ($routes) {
    // Dashboard
    $routes->get('dashboard', 'Items::dashboard');

    // Sales Page
    $routes->get('sales', 'SalesController::index');
    $routes->get('sales/transactions', 'SalesController::transactions');
    $routes->get('sales/transaction-items/(:segment)', 'SalesController::getTransactionItems/$1');

    // Stock Requests
    $routes->get('stock-requests', 'AdminRequests::index');
    $routes->post('submit-stock-request', 'AdminRequests::submitStockRequest');
    $routes->match(['get', 'post'], 'approve-request/(:num)', 'AdminRequests::approve/$1');
    $routes->match(['get', 'post'], 'reject-request/(:num)', 'AdminRequests::reject/$1');

    // Food Waste Pull-Outs
    $routes->get('pull-outs', 'PullOutController::index');
    $routes->post('approve-pull-out/(:num)', 'PullOutController::approve/$1');
    $routes->post('reject-pull-out/(:num)', 'PullOutController::reject/$1');

    // Admin POS
    $routes->get('pos', 'PosController::adminIndex');
    $routes->post('pos/sell', 'PosController::sell');

    // Staff Subgroup
    $routes->group('staff', function ($routes) {
        $routes->get('dashboard', 'StaffController::dashboard');

        // Staff POS
        $routes->get('pos', 'PosController::staffIndex');
        $routes->post('pos/sell', 'PosController::sell');  // ✅ THIS IS CORRECT

        // User Management
        $routes->get('users', 'UserManagement::index');
        $routes->get('users/create', 'UserManagement::create');
        $routes->post('users/save', 'UserManagement::save');
        $routes->get('users/edit/(:num)', 'UserManagement::edit/$1');
        $routes->post('users/update/(:num)', 'UserManagement::update/$1');
        $routes->match(['post', 'delete'], 'users/delete/(:num)', 'UserManagement::delete/$1');
    });
});

// -------------------------------------------------------------
// ITEMS ROUTES
// -------------------------------------------------------------
$routes->group('items', function ($routes) {
    $routes->get('/', 'Items::index');
    $routes->get('add', 'Items::add');
    $routes->post('store', 'Items::store');
    $routes->get('edit/(:num)', 'Items::edit/$1');
    $routes->post('update/(:num)', 'Items::update/$1');
    $routes->match(['get', 'post'], 'delete/(:num)', 'Items::delete/$1');
    $routes->post('delete-permanent/(:num)', 'Items::deletePermanent/$1');
    $routes->post('deleteMultiple', 'Items::deleteMultiple');
    $routes->post('increaseQuantity/(:num)', 'Items::increaseQuantity/$1');
    $routes->post('decreaseQuantity/(:num)', 'Items::decreaseQuantity/$1');
    $routes->post('updateMultipleQuantity', 'Items::updateMultipleQuantity');
    $routes->get('logs', 'Items::logs');
    $routes->get('expired', 'Items::expired');
    $routes->get('expiring-soon', 'Items::expiringSoon');
    $routes->get('deleted', 'Items::deleted');


    // ✅ BULK UPLOAD ROUTE (ADDED)
    $routes->post('bulk-upload', 'Items::bulkUpload');

    // Export routes
    $routes->get('export-sales-csv', 'Items::exportSalesCsv');
    $routes->get('export-logs-csv', 'Items::exportLogsCsv');
});

// -------------------------------------------------------------
// 🔥 FIX: ADD EXPLICIT ROUTE FOR STAFF POS SELL (if needed)
// -------------------------------------------------------------
// Sometimes routing gets confused with nested groups.
// Add this as a fallback:
$routes->post('admin/staff/pos/sell', 'PosController::sell');

// Also add if frontend uses /staff/pos/sell (without admin prefix)
$routes->post('staff/pos/sell', 'PosController::sell');

$routes->get('admin/stock-request-logs', 'AdminRequests::logs');

// -------------------------------------------------------------
// API ROUTES (For External Ordering Platform)
// -------------------------------------------------------------
$routes->group('api', function ($routes) {
    // Allows handling preflight requests for CORS
    $routes->options('(:any)', 'ApiController::getProducts'); 
    
    // Get all products
    $routes->get('products', 'ApiController::getProducts');
    
    // Online Orders
    $routes->post('submit-order', 'ApiController::submitOrder');
    $routes->get('pending-orders', 'ApiController::getPendingOrders');
    $routes->post('confirm-order', 'ApiController::confirmOrder');
});

// -------------------------------------------------------------
// CUSTOMER FRONTEND ROUTE
// -------------------------------------------------------------
$routes->get('order', 'CustomerOrderController::index');

// Setup route for automatic database sync
$routes->get('setup-db', 'Setup::index');