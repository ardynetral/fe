<?php

$routes->add('/wo', '\Modules\WorkOrder\Controllers\WorkOrder::index', ['filter' => 'login']);
$routes->add('/wo/add', '\Modules\WorkOrder\Controllers\WorkOrder::add', ['filter' => 'login']);
$routes->post('/wo/add', '\Modules\WorkOrder\Controllers\WorkOrder::add', ['filter' => 'login']);
$routes->add('/wo/list_data', '\Modules\WorkOrder\Controllers\WorkOrder::list_data', ['filter' => 'login']);
$routes->add('/wo/get_data_detail', '\Modules\WorkOrder\Controllers\WorkOrder::get_data_detail', ['filter' => 'login']);
$routes->add('/wo/save_all_detail', '\Modules\WorkOrder\Controllers\WorkOrder::save_all_detail', ['filter' => 'login']);
$routes->add('/wo/save_one_detail', '\Modules\WorkOrder\Controllers\WorkOrder::save_one_detail', ['filter' => 'login']);
// EDIT
$routes->add('/wo/edit/(:alphanum)', '\Modules\WorkOrder\Controllers\WorkOrder::edit/$1', ['filter' => 'login']);
// DELETE
$routes->add('/wo/delete_container', '\Modules\WorkOrder\Controllers\WorkOrder::delete_container', ['filter' => 'login']);
