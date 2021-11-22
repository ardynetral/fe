<?php

$routes->add('/vessel', '\Modules\Vessel\Controllers\Vessel::index', ['filter' => 'login']);
$routes->add('/vessel/view/(:alphanum)', '\Modules\Vessel\Controllers\Vessel::view/$1', ['filter' => 'login']);
$routes->add('/vessel/add', '\Modules\Vessel\Controllers\Vessel::add', ['filter' => 'login']);
$routes->post('/vessel/add', '\Modules\Vessel\Controllers\Vessel::add', ['filter' => 'login']);
$routes->add('/vessel/edit/(:alphanum)', '\Modules\Vessel\Controllers\Vessel::edit/$1', ['filter' => 'login']);
$routes->post('/vessel/edit/(:alphanum)', '\Modules\Vessel\Controllers\Vessel::edit/$1', ['filter' => 'login']);
$routes->add('/vessel/delete/(:alphanum)', '\Modules\Vessel\Controllers\Vessel::delete/$1', ['filter' => 'login']);