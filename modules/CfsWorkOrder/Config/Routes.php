<?php

$routes->add('/cfswo', '\Modules\CfsWorkOrder\Controllers\CfsWorkOrder::index', ['filter' => 'login']);
$routes->add('/cfswo/add', '\Modules\CfsWorkOrder\Controllers\CfsWorkOrder::add', ['filter' => 'login']);
$routes->post('/cfswo/add', '\Modules\CfsWorkOrder\Controllers\CfsWorkOrder::add', ['filter' => 'login']);
$routes->add('/cfswo/list_data', '\Modules\CfsWorkOrder\Controllers\CfsWorkOrder::list_data', ['filter' => 'login']);
$routes->add('/cfswo/get_data_detail/(:alphanum)', '\Modules\CfsWorkOrder\Controllers\CfsWorkOrder::get_data_detail/$1', ['filter' => 'login']);
$routes->add('/cfswo/save_all_detail', '\Modules\CfsWorkOrder\Controllers\CfsWorkOrder::save_all_detail', ['filter' => 'login']);
$routes->add('/cfswo/save_one_detail', '\Modules\CfsWorkOrder\Controllers\CfsWorkOrder::save_one_detail', ['filter' => 'login']);
// EDIT
$routes->add('/cfswo/edit/(:alphanum)', '\Modules\CfsWorkOrder\Controllers\CfsWorkOrder::edit/$1', ['filter' => 'login']);
$routes->post('/cfswo/edit/(:alphanum)', '\Modules\CfsWorkOrder\Controllers\CfsWorkOrder::edit/$1', ['filter' => 'login']);
// DELETE
$routes->add('/cfswo/delete_container', '\Modules\CfsWorkOrder\Controllers\CfsWorkOrder::delete_container', ['filter' => 'login']);
// PRINT
$routes->add('/cfswo/print/(:alphanum)', '\Modules\CfsWorkOrder\Controllers\CfsWorkOrder::print/$1', ['filter' => 'login']);


// RECEPT
$routes->add('/cfswo/get_data_receipt', '\Modules\CfsWorkOrder\Controllers\CfsWorkOrder::get_data_receipt', ['filter' => 'login']);
$routes->add('/cfswo/insertReceipt/(:alphanum)', '\Modules\CfsWorkOrder\Controllers\CfsWorkOrder::insertReceipt/$1', ['filter' => 'login']);
$routes->post('/cfswo/update_receipt/(:alphanum)', '\Modules\CfsWorkOrder\Controllers\CfsWorkOrder::update_receipt/$1', ['filter' => 'login']);

// RAB
$routes->add('/cfswo/get_data_rab', '\Modules\CfsWorkOrder\Controllers\CfsWorkOrder::get_data_rab', ['filter' => 'login']);
$routes->add('/cfswo/insertRab/(:alphanum)', '\Modules\CfsWorkOrder\Controllers\CfsWorkOrder::insertRab/$1', ['filter' => 'login']);
$routes->post('/cfswo/update_rab/(:alphanum)', '\Modules\CfsWorkOrder\Controllers\CfsWorkOrder::update_rab/$1', ['filter' => 'login']);

// CONTAINER
$routes->add('/cfswo/get_data_container', '\Modules\CfsWorkOrder\Controllers\CfsWorkOrder::get_data_container', ['filter' => 'login']);
$routes->post('/cfswo/insertContainer', '\Modules\CfsWorkOrder\Controllers\CfsWorkOrder::insertContainer', ['filter' => 'login']);
$routes->post('/cfswo/update_container/(:alphanum)', '\Modules\CfsWorkOrder\Controllers\CfsWorkOrder::update_container/$1', ['filter' => 'login']);
$routes->add('/cfswo/checkContainerOut', '\Modules\CfsWorkOrder\Controllers\CfsWorkOrder::checkContainerOut', ['filter' => 'login']);
$routes->add('/cfswo/checkContainerIn', '\Modules\CfsWorkOrder\Controllers\CfsWorkOrder::checkContainerIn', ['filter' => 'login']);
$routes->add('/cfswo/ajax_ccode_listOne/(:alphanum)', '\Modules\CfsWorkOrder\Controllers\CfsWorkOrder::ajax_ccode_listOne/$1', ['filter' => 'login']);