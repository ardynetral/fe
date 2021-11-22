<?php

$routes->add('/material', '\Modules\Material\Controllers\Material::index', ['filter' => 'login']);
$routes->add('/material/view/(:alphanum)', '\Modules\Material\Controllers\Material::view/$1', ['filter' => 'login']);
$routes->add('/material/add', '\Modules\Material\Controllers\Material::add', ['filter' => 'login']);
$routes->post('/material/add', '\Modules\Material\Controllers\Material::add', ['filter' => 'login']);
$routes->add('/material/edit/(:alphanum)', '\Modules\Material\Controllers\Material::edit/$1', ['filter' => 'login']);
$routes->post('/material/edit/(:alphanum)', '\Modules\Material\Controllers\Material::edit/$1', ['filter' => 'login']);
$routes->add('/material/delete/(:alphanum)', '\Modules\Material\Controllers\Material::delete/$1', ['filter' => 'login']);
