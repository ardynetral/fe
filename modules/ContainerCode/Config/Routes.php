<?php

$routes->add('/containercode', '\Modules\ContainerCode\Controllers\ContainerCode::index', ['filter' => 'login']);
$routes->add('/containercode/ajax_ccode', '\Modules\ContainerCode\Controllers\ContainerCode::ajax_ccode', ['filter' => 'login']);
$routes->add('/containercode/view/(:alphanum)', '\Modules\ContainerCode\Controllers\ContainerCode::view/$1', ['filter' => 'login']);
$routes->add('/containercode/add', '\Modules\ContainerCode\Controllers\ContainerCode::add', ['filter' => 'login']);
$routes->post('/containercode/add', '\Modules\ContainerCode\Controllers\ContainerCode::add', ['filter' => 'login']);
$routes->add('/containercode/edit/(:alphanum)', '\Modules\ContainerCode\Controllers\ContainerCode::edit/$1', ['filter' => 'login']);
$routes->post('/containercode/edit/(:alphanum)', '\Modules\ContainerCode\Controllers\ContainerCode::edit/$1', ['filter' => 'login']);
$routes->add('/containercode/delete/(:alphanum)', '\Modules\ContainerCode\Controllers\ContainerCode::delete/$1', ['filter' => 'login']);
$routes->post('/containercode/cek_cccode', '\Modules\ContainerCode\Controllers\ContainerCode::cek_cccode', ['filter' => 'login']);