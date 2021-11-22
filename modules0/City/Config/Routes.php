<?php

$routes->add('/city', '\Modules\City\Controllers\City::index', ['filter' => 'login']);
$routes->add('/city/view/(:alphanum)', '\Modules\City\Controllers\City::view/$1', ['filter' => 'login']);
$routes->add('/city/add', '\Modules\City\Controllers\City::add', ['filter' => 'login']);
$routes->post('/city/add', '\Modules\City\Controllers\City::add', ['filter' => 'login']);
$routes->add('/city/edit/(:alphanum)', '\Modules\City\Controllers\City::edit/$1', ['filter' => 'login']);
$routes->post('/city/edit/(:alphanum)', '\Modules\City\Controllers\City::edit/$1', ['filter' => 'login']);
$routes->add('/city/delete/(:alphanum)', '\Modules\City\Controllers\City::delete/$1', ['filter' => 'login']);
$routes->add('/city/ajax_country', '\Modules\City\Controllers\City::ajax_country', ['filter' => 'login']);
