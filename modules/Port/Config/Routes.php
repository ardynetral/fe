<?php

$routes->add('/port', '\Modules\Port\Controllers\Port::index', ['filter' => 'login']);
$routes->add('/port/view/(:alphanum)', '\Modules\Port\Controllers\Port::view/$1', ['filter' => 'login']);
$routes->add('/port/add', '\Modules\Port\Controllers\Port::add', ['filter' => 'login']);
$routes->post('/port/add', '\Modules\Port\Controllers\Port::add', ['filter' => 'login']);
$routes->add('/port/edit/(:alphanum)', '\Modules\Port\Controllers\Port::edit/$1', ['filter' => 'login']);
$routes->post('/port/edit/(:alphanum)', '\Modules\Port\Controllers\Port::edit/$1', ['filter' => 'login']);
$routes->add('/port/delete/(:alphanum)', '\Modules\Port\Controllers\Port::delete/$1', ['filter' => 'login']);
// $routes->post('/port/cek_port', '\Modules\Port\Controllers\Port::cek_country', ['filter' => 'login']);
// $routes->add('/port/ajax_port', '\Modules\Port\Controllers\Port::ajax_country', ['filter' => 'login']);