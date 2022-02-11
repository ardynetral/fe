<?php

$routes->add('/estimation', '\Modules\Estimation\Controllers\Estimation::index', ['filter' => 'login']);
$routes->add('/estimation/add', '\Modules\Estimation\Controllers\Estimation::add', ['filter' => 'login']);
$routes->add('/estimation/edit/(:alphanum)', '\Modules\Estimation\Controllers\Estimation::edit/$1', ['filter' => 'login']);
$routes->add('/estimation/list_data', '\Modules\Estimation\Controllers\Estimation::list_data', ['filter' => 'login']);
$routes->add('/estimation/getDataEstimasi', '\Modules\Estimation\Controllers\Estimation::getDataEstimasi', ['filter' => 'login']);
$routes->add('/estimation/getOneEstimasi/(:alphanum)', '\Modules\Estimation\Controllers\Estimation::getOneEstimasi/$1', ['filter' => 'login']);
// DETAIL
$routes->add('/estimation/add_detail', '\Modules\Estimation\Controllers\Estimation::add_detail', ['filter' => 'login']);
$routes->add('/estimation/save_detail', '\Modules\Estimation\Controllers\Estimation::save_detail', ['filter' => 'login']);
$routes->add('/estimation/update_detail', '\Modules\Estimation\Controllers\Estimation::update_detail', ['filter' => 'login']);
$routes->add('/estimation/getFileList/(:alphanum)', '\Modules\Estimation\Controllers\Estimation::getFileList/$1', ['filter' => 'login']);
$routes->add('/estimation/delete_detail', '\Modules\Estimation\Controllers\Estimation::delete_detail', ['filter' => 'login']);
