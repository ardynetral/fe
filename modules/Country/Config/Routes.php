<?php

$routes->add('/country', '\Modules\Country\Controllers\Country::index', ['filter' => 'login']);
$routes->add('/country/view/(:alphanum)', '\Modules\Country\Controllers\Country::view/$1', ['filter' => 'login']);
$routes->add('/country/add', '\Modules\Country\Controllers\Country::add', ['filter' => 'login']);
$routes->post('/country/add', '\Modules\Country\Controllers\Country::add', ['filter' => 'login']);
$routes->add('/country/edit/(:alphanum)', '\Modules\Country\Controllers\Country::edit/$1', ['filter' => 'login']);
$routes->post('/country/edit/(:alphanum)', '\Modules\Country\Controllers\Country::edit/$1', ['filter' => 'login']);
$routes->add('/country/delete/(:alphanum)', '\Modules\Country\Controllers\Country::delete/$1', ['filter' => 'login']);
