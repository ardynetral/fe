<?php

$routes->add('/approval', '\Modules\Approval\Controllers\Approval::index', ['filter' => 'login']);
$routes->add('/approval/add', '\Modules\Approval\Controllers\Approval::add', ['filter' => 'login']);
$routes->add('/approval/view/(:alphanum)', '\Modules\Approval\Controllers\Approval::view/$1', ['filter' => 'login']);
$routes->add('/approval/next_estimasi/(:alphanum)', '\Modules\Approval\Controllers\Approval::next_estimasi/$1', ['filter' => 'login']);
$routes->add('/approval/final_estimasi', '\Modules\Approval\Controllers\Approval::final_estimasi', ['filter' => 'login']);

$routes->add('/approval/list_data', '\Modules\Approval\Controllers\Approval::list_data', ['filter' => 'login']);
$routes->add('/approval/getDataEstimasi', '\Modules\Approval\Controllers\Approval::getDataEstimasi', ['filter' => 'login']);
$routes->add('/approval/getOneEstimasi/(:alphanum)', '\Modules\Approval\Controllers\Approval::getOneEstimasi/$1', ['filter' => 'login']);
// DETAIL
$routes->add('/approval/add_detail', '\Modules\Approval\Controllers\Approval::add_detail', ['filter' => 'login']);
$routes->add('/approval/save_detail', '\Modules\Approval\Controllers\Approval::save_detail', ['filter' => 'login']);
$routes->add('/approval/update_detail', '\Modules\Approval\Controllers\Approval::update_detail', ['filter' => 'login']);
$routes->add('/approval/getFileList/(:alphanum)', '\Modules\Approval\Controllers\Approval::getFileList/$1', ['filter' => 'login']);
$routes->add('/approval/delete_detail', '\Modules\Approval\Controllers\Approval::delete_detail', ['filter' => 'login']);
$routes->add('/approval/delete_detail_edit', '\Modules\Approval\Controllers\Approval::delete_detail_edit', ['filter' => 'login']);
$routes->add('/approval/print/(:alphanum)/(:alphanum)', '\Modules\Approval\Controllers\Approval::print/$1/$2', ['filter' => 'login']);
$routes->add('/approval/calculateTotalCost', '\Modules\Approval\Controllers\Approval::calculateTotalCost', ['filter' => 'login']);