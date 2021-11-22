<?php

$routes->add('/inventorynonavi', '\Modules\Inventorynonavi\Controllers\Inventorynonavi::index', ['filter' => 'login']);
$routes->add('/inventorynonavi/view/(:alphanum)', '\Modules\Inventorynonavi\Controllers\Inventorynonavi::view/$1', ['filter' => 'login']);
$routes->add('/inventorynonavi/add', '\Modules\Inventorynonavi\Controllers\Inventorynonavi::add', ['filter' => 'login']);
$routes->post('/inventorynonavi/add', '\Modules\Inventorynonavi\Controllers\Inventorynonavi::add', ['filter' => 'login']);
$routes->add('/inventorynonavi/edit/(:alphanum)', '\Modules\Inventorynonavi\Controllers\Inventorynonavi::edit/$1', ['filter' => 'login']);
$routes->post('/inventorynonavi/edit/(:alphanum)', '\Modules\Inventorynonavi\Controllers\Inventorynonavi::edit/$1', ['filter' => 'login']);
$routes->add('/inventorynonavi/delete/(:alphanum)', '\Modules\Inventorynonavi\Controllers\Inventorynonavi::delete/$1', ['filter' => 'login']);
$routes->add('/inventorynonavi/ajax_country', '\Modules\Inventorynonavi\Controllers\Inventorynonavi::ajax_country', ['filter' => 'login']);
// $routes->add('/dailymovementout/ajax_customer', '\Modules\Principal\Controllers\Principal::ajax_country', ['filter' => 'login']);
