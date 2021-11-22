<?php

$routes->add('/param', '\Modules\Param\Controllers\Param::index', ['filter' => 'login']);
$routes->add('/param/view/(:alphanum)', '\Modules\Param\Controllers\Param::view/$1', ['filter' => 'login']);
$routes->add('/param/add', '\Modules\Param\Controllers\Param::add', ['filter' => 'login']);
$routes->post('/param/add', '\Modules\Param\Controllers\Param::add', ['filter' => 'login']);
$routes->add('/param/edit/(:alphanum)', '\Modules\Param\Controllers\Param::edit/$1', ['filter' => 'login']);
$routes->post('/param/edit/(:alphanum)', '\Modules\Param\Controllers\Param::edit/$1', ['filter' => 'login']);
$routes->add('/param/delete/(:alphanum)', '\Modules\Param\Controllers\Param::delete/$1', ['filter' => 'login']);
