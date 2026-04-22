<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 * 
 * Darurat Bencana - Route Definitions
 */

// ============================================================
// PUBLIC ROUTES
// ============================================================

// Landing page
$routes->get('/', 'Home::index');

// Report form
$routes->get('/lapor', 'Home::lapor');
$routes->post('/lapor/submit', 'Home::submitLaporan');

// ============================================================
// AUTHENTICATION ROUTES
// ============================================================

$routes->get('/auth/login', 'Auth::login');
$routes->post('/auth/login', 'Auth::processLogin');
$routes->get('/auth/logout', 'Auth::logout');

// ============================================================
// ADMIN ROUTES (Protected by AuthFilter)
// ============================================================

// Dashboard
$routes->get('/admin/dashboard', 'Dashboard::index');
$routes->get('/admin/api/stats', 'Dashboard::getStats');

// Laporan Management
$routes->get('/admin/laporan', 'Dashboard::laporan');
$routes->get('/admin/laporan/export', 'Dashboard::exportCsv');
$routes->get('/admin/laporan/(:num)', 'Dashboard::detailLaporan/$1');
$routes->post('/admin/laporan/delete/(:num)', 'Dashboard::deleteLaporan/$1');
$routes->post('/admin/laporan/status/(:num)', 'Dashboard::updateStatus/$1');

// Kontak CRUD
$routes->get('/admin/kontak', 'Kontak::index');
$routes->post('/admin/kontak/store', 'Kontak::store');
$routes->post('/admin/kontak/update/(:num)', 'Kontak::update/$1');
$routes->post('/admin/kontak/delete/(:num)', 'Kontak::delete/$1');

// Monitoring
$routes->get('/admin/monitoring', 'Monitoring::index');
$routes->get('/admin/monitoring/latest', 'Monitoring::getLatestReports');
