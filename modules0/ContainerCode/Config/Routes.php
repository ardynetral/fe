<?php

$routes->add('/ccode', '\Modules\ContainerCode\Controllers\ContainerCode::index', ['filter' => 'login']);
$routes->add('/ccode/ajax_ccode', '\Modules\ContainerCode\Controllers\ContainerCode::ajax_ccode', ['filter' => 'login']);
$routes->add('/ccode/view/(:alphanum)', '\Modules\ContainerCode\Controllers\ContainerCode::view/$1', ['filter' => 'login']);
$routes->add('/ccode/add', '\Modules\ContainerCode\Controllers\ContainerCode::add', ['filter' => 'login']);
$routes->post('/ccode/add', '\Modules\ContainerCode\Controllers\ContainerCode::add', ['filter' => 'login']);
$routes->add('/ccode/edit/(:alphanum)', '\Modules\ContainerCode\Controllers\ContainerCode::edit/$1', ['filter' => 'login']);
$routes->post('/ccode/edit/(:alphanum)', '\Modules\ContainerCode\Controllers\ContainerCode::edit/$1', ['filter' => 'login']);
$routes->add('/ccode/delete/(:alphanum)', '\Modules\ContainerCode\Controllers\ContainerCode::delete/$1', ['filter' => 'login']);
$routes->post('/ccode/cek_cccode', '\Modules\ContainerCode\Controllers\ContainerCode::cek_cccode', ['filter' => 'login']);