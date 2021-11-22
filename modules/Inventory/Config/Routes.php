<?php

$routes->add('/inventory', '\Modules\Inventory\Controllers\Inventory::index', ['filter' => 'login']);
$routes->add('/inventory/view/(:alphanum)', '\Modules\Inventory\Controllers\Inventory::view/$1', ['filter' => 'login']);
$routes->add('/inventory/add', '\Modules\Inventory\Controllers\Inventory::add', ['filter' => 'login']);
$routes->post('/inventory/add', '\Modules\Inventory\Controllers\Inventory::add', ['filter' => 'login']);
$routes->add('/inventory/edit/(:alphanum)', '\Modules\Inventory\Controllers\Inventory::edit/$1', ['filter' => 'login']);
$routes->post('/inventory/edit/(:alphanum)', '\Modules\Inventory\Controllers\Inventory::edit/$1', ['filter' => 'login']);
$routes->add('/inventory/delete/(:alphanum)', '\Modules\Inventory\Controllers\Inventory::delete/$1', ['filter' => 'login']);
$routes->add('/inventory/ajax_country', '\Modules\Inventory\Controllers\Inventory::ajax_country', ['filter' => 'login']);
// $routes->add('/dailymovementout/ajax_customer', '\Modules\Principal\Controllers\Principal::ajax_country', ['filter' => 'login']);
