<?php

$routes->add('/componen', '\Modules\Componen\Controllers\Componen::index', ['filter' => 'login']);
$routes->add('/componen/view/(:alphanum)', '\Modules\Componen\Controllers\Componen::view/$1', ['filter' => 'login']);
$routes->add('/componen/add', '\Modules\Componen\Controllers\Componen::add', ['filter' => 'login']);
$routes->post('/componen/add', '\Modules\Componen\Controllers\Componen::add', ['filter' => 'login']);
$routes->add('/componen/edit/(:alphanum)', '\Modules\Componen\Controllers\Componen::edit/$1', ['filter' => 'login']);
$routes->post('/componen/edit/(:alphanum)', '\Modules\Componen\Controllers\Componen::edit/$1', ['filter' => 'login']);
$routes->add('/componen/delete/(:alphanum)', '\Modules\Componen\Controllers\Componen::delete/$1', ['filter' => 'login']);
