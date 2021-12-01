<?php

$routes->add('/trucker', '\Modules\Trucker\Controllers\Trucker::index', ['filter' => 'login']);
$routes->add('/trucker/list_data', '\Modules\Trucker\Controllers\Trucker::list_data', ['filter' => 'login']);
$routes->add('/trucker/view/(:alphanum)', '\Modules\Trucker\Controllers\Trucker::view/$1', ['filter' => 'login']);
$routes->add('/trucker/add', '\Modules\Trucker\Controllers\Trucker::add', ['filter' => 'login']);
$routes->post('/trucker/add', '\Modules\Trucker\Controllers\Trucker::add', ['filter' => 'login']);
$routes->add('/trucker/edit/(:alphanum)', '\Modules\Trucker\Controllers\Trucker::edit/$1', ['filter' => 'login']);
$routes->post('/trucker/edit/(:alphanum)', '\Modules\Trucker\Controllers\Trucker::edit/$1', ['filter' => 'login']);
$routes->add('/trucker/delete/(:alphanum)', '\Modules\Trucker\Controllers\Trucker::delete/$1', ['filter' => 'login']);
$routes->add('/trucker/ajax_country', '\Modules\Trucker\Controllers\Trucker::ajax_country', ['filter' => 'login']);
