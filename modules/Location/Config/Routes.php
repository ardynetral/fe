<?php

$routes->add('/location', '\Modules\Location\Controllers\Location::index', ['filter' => 'login']);
$routes->add('/location/list_data', '\Modules\Location\Controllers\Location::list_data', ['filter' => 'login']);
$routes->add('/location/view/(:alphanum)', '\Modules\Location\Controllers\Location::view/$1', ['filter' => 'login']);
$routes->add('/location/add', '\Modules\Location\Controllers\Location::add', ['filter' => 'login']);
$routes->post('/location/add', '\Modules\Location\Controllers\Location::add', ['filter' => 'login']);
$routes->add('/location/edit/(:alphanum)', '\Modules\Location\Controllers\Location::edit/$1', ['filter' => 'login']);
$routes->post('/location/edit/(:alphanum)', '\Modules\Location\Controllers\Location::edit/$1', ['filter' => 'login']);
$routes->add('/location/delete/(:alphanum)', '\Modules\Location\Controllers\Location::delete/$1', ['filter' => 'login']);