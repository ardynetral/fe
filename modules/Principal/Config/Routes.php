<?php

$routes->add('/principal', '\Modules\Principal\Controllers\Principal::index', ['filter' => 'login']);
$routes->add('/principal/view/(:alphanum)', '\Modules\Principal\Controllers\Principal::view/$1', ['filter' => 'login']);
$routes->add('/principal/add', '\Modules\Principal\Controllers\Principal::add', ['filter' => 'login']);
$routes->post('/principal/add', '\Modules\Principal\Controllers\Principal::add', ['filter' => 'login']);
$routes->add('/principal/edit/(:alphanum)', '\Modules\Principal\Controllers\Principal::edit/$1', ['filter' => 'login']);
$routes->post('/principal/edit/(:alphanum)', '\Modules\Principal\Controllers\Principal::edit/$1', ['filter' => 'login']);
$routes->add('/principal/delete/(:alphanum)', '\Modules\Principal\Controllers\Principal::delete/$1', ['filter' => 'login']);
$routes->add('/principal/ajax_country', '\Modules\Principal\Controllers\Principal::ajax_country', ['filter' => 'login']);
// $routes->add('/principal/ajax_customer', '\Modules\Principal\Controllers\Principal::ajax_country', ['filter' => 'login']);
