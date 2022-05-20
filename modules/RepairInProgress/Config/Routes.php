<?php

$routes->add('/rip', '\Modules\RepairInProgress\Controllers\RepairInProgress::index', ['filter' => 'login']);
$routes->add('/rip/add', '\Modules\RepairInProgress\Controllers\RepairInProgress::add', ['filter' => 'login']);
$routes->add('/rip/edit/(:alphanum)', '\Modules\RepairInProgress\Controllers\RepairInProgress::edit/$1', ['filter' => 'login']);
$routes->add('/rip/list_data', '\Modules\RepairInProgress\Controllers\RepairInProgress::list_data', ['filter' => 'login']);
$routes->add('/rip/getDataEstimasi', '\Modules\RepairInProgress\Controllers\RepairInProgress::getDataEstimasi', ['filter' => 'login']);
$routes->add('/rip/getOneEstimasi/(:alphanum)', '\Modules\RepairInProgress\Controllers\RepairInProgress::getOneEsftimasi/$1', ['filter' => 'login']);
// DETAIL
$routes->add('/rip/add_detail', '\Modules\RepairInProgress\Controllers\RepairInProgress::add_detail', ['filter' => 'login']);
$routes->add('/rip/save_detail', '\Modules\RepairInProgress\Controllers\RepairInProgress::save_detail', ['filter' => 'login']);
$routes->add('/rip/update_detail', '\Modules\RepairInProgress\Controllers\RepairInProgress::update_detail', ['filter' => 'login']);
$routes->add('/rip/getFileList/(:alphanum)/(:alphanum)', '\Modules\RepairInProgress\Controllers\RepairInProgress::getFileList/$1/$2', ['filter' => 'login']);
$routes->add('/rip/delete_detail', '\Modules\RepairInProgress\Controllers\RepairInProgress::delete_detail', ['filter' => 'login']);
$routes->add('/rip/delete_detail_edit', '\Modules\RepairInProgress\Controllers\RepairInProgress::delete_detail_edit', ['filter' => 'login']);
$routes->add('/rip/print/(:alphanum)/(:alphanum)', '\Modules\RepairInProgress\Controllers\RepairInProgress::print/$1/$2', ['filter' => 'login']);
$routes->add('/rip/calculateTotalCost', '\Modules\RepairInProgress\Controllers\RepairInProgress::calculateTotalCost', ['filter' => 'login']);
$routes->add('/rip/finish_repair', '\Modules\RepairInProgress\Controllers\RepairInProgress::finish_repair', ['filter' => 'login']);
$routes->add('/rip/finish_cleaning', '\Modules\RepairInProgress\Controllers\RepairInProgress::finish_cleaning', ['filter' => 'login']);

