<?php

$routes->add('/shipper', '\Modules\Shipper\Controllers\Shipper::index', ['filter' => 'login']);
$routes->add('/shipper/list_data', '\Modules\Shipper\Controllers\Shipper::list_data', ['filter' => 'login']);
$routes->add('/shipper/view/(:alphanum)', '\Modules\Shipper\Controllers\Shipper::view/$1', ['filter' => 'login']);
$routes->add('/shipper/add', '\Modules\Shipper\Controllers\Shipper::add', ['filter' => 'login']);
$routes->post('/shipper/add', '\Modules\Shipper\Controllers\Shipper::add', ['filter' => 'login']);
$routes->add('/shipper/edit/(:alphanum)', '\Modules\Shipper\Controllers\Shipper::edit/$1', ['filter' => 'login']);
$routes->post('/shipper/edit/(:alphanum)', '\Modules\Shipper\Controllers\Shipper::edit/$1', ['filter' => 'login']);
$routes->add('/shipper/delete/(:alphanum)', '\Modules\Shipper\Controllers\Shipper::delete/$1', ['filter' => 'login']);
$routes->add('/shipper/ajax_country', '\Modules\Shipper\Controllers\Shipper::ajax_country', ['filter' => 'login']);
