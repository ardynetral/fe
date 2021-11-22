<?php

$routes->add('/losc', '\Modules\Losc\Controllers\Losc::index', ['filter' => 'login']);
$routes->add('/losc/view/(:alphanum)', '\Modules\Losc\Controllers\Losc::view/$1', ['filter' => 'login']);
$routes->add('/losc/add', '\Modules\Losc\Controllers\Losc::add', ['filter' => 'login']);
$routes->post('/losc/add', '\Modules\Losc\Controllers\Losc::add', ['filter' => 'login']);
$routes->add('/losc/edit/(:alphanum)', '\Modules\Losc\Controllers\Losc::edit/$1', ['filter' => 'login']);
$routes->post('/losc/edit/(:alphanum)', '\Modules\Losc\Controllers\Losc::edit/$1', ['filter' => 'login']);
$routes->add('/losc/delete/(:alphanum)', '\Modules\Losc\Controllers\Losc::delete/$1', ['filter' => 'login']);
$routes->add('/losc/ajax_country', '\Modules\Losc\Controllers\Losc::ajax_country', ['filter' => 'login']);
// $routes->add('/dailymovementout/ajax_customer', '\Modules\Principal\Controllers\Principal::ajax_country', ['filter' => 'login']);
