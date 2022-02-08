<?php

$routes->add('/estimation', '\Modules\Estimation\Controllers\Estimation::index', ['filter' => 'login']);
$routes->add('/estimation/add', '\Modules\Estimation\Controllers\Estimation::add', ['filter' => 'login']);
$routes->add('/estimation/add_detail', '\Modules\Estimation\Controllers\Estimation::add_detail', ['filter' => 'login']);
// $routes->add('/prain/view/(:alphanum)', '\Modules\PraIn\Controllers\PraIn::view/$1', ['filter' => 'login']);
// $routes->add('/prain/add', '\Modules\PraIn\Controllers\PraIn::add', ['filter' => 'login']);
// $routes->add('/prain/edit/(:alphanum)', '\Modules\PraIn\Controllers\PraIn::edit/$1', ['filter' => 'login']);
// $routes->add('/prain/delete/(:alphanum)', '\Modules\PraIn\Controllers\PraIn::delete/$1', ['filter' => 'login']);
$routes->add('/estimation/list_data', '\Modules\Estimation\Controllers\Estimation::list_data', ['filter' => 'login']);
$routes->add('/estimation/getDataEstimasi', '\Modules\Estimation\Controllers\Estimation::getDataEstimasi', ['filter' => 'login']);
