<?php

$routes->add('/mnrtariff', '\Modules\MnrTariff\Controllers\MnrTariff::index', ['filter' => 'login']);
$routes->add('/mnrtariff/list_data', '\Modules\MnrTariff\Controllers\MnrTariff::list_data', ['filter' => 'login']);
$routes->add('/mnrtariff/view/(:alphanum)', '\Modules\MnrTariff\Controllers\MnrTariff::view/$1', ['filter' => 'login']);
$routes->add('/mnrtariff/add', '\Modules\MnrTariff\Controllers\MnrTariff::add', ['filter' => 'login']);
$routes->post('/mnrtariff/add', '\Modules\MnrTariff\Controllers\MnrTariff::add', ['filter' => 'login']);
$routes->add('/mnrtariff/edit/(:alphanum)', '\Modules\MnrTariff\Controllers\MnrTariff::edit/$1', ['filter' => 'login']);
$routes->post('/mnrtariff/edit/(:alphanum)', '\Modules\MnrTariff\Controllers\MnrTariff::edit/$1', ['filter' => 'login']);
$routes->add('/mnrtariff/delete', '\Modules\MnrTariff\Controllers\MnrTariff::delete', ['filter' => 'login']);

$routes->add('/mnrtariff/get_component_desc', '\Modules\MnrTariff\Controllers\MnrTariff::get_component_desc', ['filter' => 'login']);
$routes->add('/mnrtariff/get_repair_desc', '\Modules\MnrTariff\Controllers\MnrTariff::get_repair_desc', ['filter' => 'login']);