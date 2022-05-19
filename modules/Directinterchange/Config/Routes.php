<?php

$routes->add('/directinterchange', '\Modules\Directinterchange\Controllers\Directinterchange::index', ['filter' => 'login']);
$routes->add('/directinterchange/add', '\Modules\Directinterchange\Controllers\Directinterchange::add', ['filter' => 'login']);
$routes->post('/directinterchange/add', '\Modules\Directinterchange\Controllers\Directinterchange::add', ['filter' => 'login']);
$routes->add('/directinterchange/list_data', '\Modules\Directinterchange\Controllers\Directinterchange::list_data', ['filter' => 'login']);
$routes->add('/directinterchange/ajax_prcode_listOne', '\Modules\Directinterchange\Controllers\Directinterchange::ajax_prcode_listOne', ['filter' => 'login']);
$routes->add('/directinterchange/getContainer', '\Modules\Directinterchange\Controllers\Directinterchange::getContainer', ['filter' => 'login']);