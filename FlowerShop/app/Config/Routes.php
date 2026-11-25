<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Home & About Routes
$routes->get('/', 'Pages::index');
$routes->get('about', 'Pages::about');
// Customer Routes
$routes->get('customers', 'CustomerController::index');
$routes->get('customers/new', 'CustomerController::new');
$routes->post('customers/create', 'CustomerController::create');
$routes->get('customers/edit/(:num)', 'CustomerController::edit/$1');
$routes->post('customers/update/(:num)', 'CustomerController::update/$1');

//Product Routes
$routes->get('products', 'ProductController::index');              // List all products
$routes->get('products/new', 'ProductController::new');           // Show "Add Product" form
$routes->post('products', 'ProductController::create');           // Save new product
$routes->get('products/(:num)/edit', 'ProductController::edit/$1'); // Show "Edit Product" form
$routes->put('products/(:num)', 'ProductController::update/$1');    // Update existing product (via _method=PUT)
$routes->post('products/(:num)', 'ProductController::update/$1');   // Fallback if form posts directly without method spoofing
$routes->delete('products/(:num)', 'ProductController::delete/$1'); // Delete product (via _method=DELETE)

//Sales Routes
// Sales routes
$routes->get('sales', 'SaleController::index');
$routes->get('sales/new', 'SaleController::new');
$routes->post('sales', 'SaleController::create');
$routes->get('sales/(:num)', 'SaleController::show/$1');
$routes->delete('sales/(:num)', 'SaleController::delete/$1');

// User Routes
$routes->get('users', 'UserController::index');
$routes->get('users/new', 'UserController::new');
$routes->post('users/create', 'UserController::create');
$routes->get('users/edit/(:num)', 'UserController::edit/$1');
$routes->post('users/update/(:num)', 'UserController::update/$1');
