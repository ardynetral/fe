<?php

$routes->add('dailymovementin', '\Modules\Dailymovementin\Controllers\Dailymovementin::index', ['filter' => 'login']);
$routes->add('dailymovementin/view/(:alphanum)', '\Modules\Dailymovementin\Controllers\Dailymovementin::view/$1', ['filter' => 'login']);
$routes->add('dailymovementin/add', '\Modules\Dailymovementin\Controllers\Dailymovementin::add', ['filter' => 'login']);
$routes->post('dailymovementin/add', '\Modules\Dailymovementin\Controllers\Dailymovementin::add', ['filter' => 'login']);
$routes->add('dailymovementin/edit/(:alphanum)', '\Modules\Dailymovementin\Controllers\Dailymovementin::edit/$1', ['filter' => 'login']);
$routes->post('dailymovementin/edit/(:alphanum)', '\Modules\Dailymovementin\Controllers\Dailymovementin::edit/$1', ['filter' => 'login']);
$routes->add('dailymovementin/delete/(:alphanum)', '\Modules\Dailymovementin\Controllers\Dailymovementin::delete/$1', ['filter' => 'login']);
$routes->add('dailymovementin/ajax_country', '\Modules\Dailymovementin\Controllers\Dailymovementin::ajax_country', ['filter' => 'login']);
// $routes->add('/principal/ajax_customer', '\Modules\Principal\Controllers\Principal::ajax_country', ['filter' => 'login']);
