<?php

$routes->add('/cfswo', '\Modules\CfsWorkOrder\Controllers\CfsWorkOrder::index', ['filter' => 'login']);
$routes->add('/cfswo/add', '\Modules\CfsWorkOrder\Controllers\CfsWorkOrder::add', ['filter' => 'login']);
$routes->post('/cfswo/add', '\Modules\CfsWorkOrder\Controllers\CfsWorkOrder::add', ['filter' => 'login']);
$routes->add('/cfswo/list_data', '\Modules\CfsWorkOrder\Controllers\CfsWorkOrder::list_data', ['filter' => 'login']);
$routes->add('/cfswo/get_data_detail', '\Modules\CfsWorkOrder\Controllers\CfsWorkOrder::get_data_detail', ['filter' => 'login']);
$routes->add('/cfswo/save_all_detail', '\Modules\CfsWorkOrder\Controllers\CfsWorkOrder::save_all_detail', ['filter' => 'login']);
$routes->add('/cfswo/save_one_detail', '\Modules\CfsWorkOrder\Controllers\CfsWorkOrder::save_one_detail', ['filter' => 'login']);
// EDIT
$routes->add('/cfswo/edit/(:alphanum)', '\Modules\CfsWorkOrder\Controllers\CfsWorkOrder::edit/$1', ['filter' => 'login']);
// DELETE
$routes->add('/cfswo/delete_container', '\Modules\CfsWorkOrder\Controllers\CfsWorkOrder::delete_container', ['filter' => 'login']);
// PRINT
$routes->add('/cfswo/print/(:alphanum)', '\Modules\CfsWorkOrder\Controllers\CfsWorkOrder::print/$1', ['filter' => 'login']);


// RECEPT
$routes->add('/cfswo/get_data_receipt', '\Modules\CfsWorkOrder\Controllers\CfsWorkOrder::get_data_receipt', ['filter' => 'login']);
$routes->post('/cfswo/update_receipt', '\Modules\CfsWorkOrder\Controllers\CfsWorkOrder::update_receipt', ['filter' => 'login']);

