<?php

$routes->add('/consignee', '\Modules\Consignee\Controllers\Consignee::index', ['filter' => 'login']);
$routes->add('/consignee/list_data', '\Modules\Consignee\Controllers\Consignee::list_data', ['filter' => 'login']);
$routes->add('/consignee/view/(:alphanum)', '\Modules\Consignee\Controllers\Consignee::view/$1', ['filter' => 'login']);
$routes->add('/consignee/add', '\Modules\Consignee\Controllers\Consignee::add', ['filter' => 'login']);
$routes->post('/consignee/add', '\Modules\Consignee\Controllers\Consignee::add', ['filter' => 'login']);
$routes->add('/consignee/edit/(:alphanum)', '\Modules\Consignee\Controllers\Consignee::edit/$1', ['filter' => 'login']);
$routes->post('/consignee/edit/(:alphanum)', '\Modules\Consignee\Controllers\Consignee::edit/$1', ['filter' => 'login']);
$routes->add('/consignee/delete/(:alphanum)', '\Modules\Consignee\Controllers\Consignee::delete/$1', ['filter' => 'login']);
$routes->add('/consignee/ajax_country', '\Modules\Consignee\Controllers\Consignee::ajax_country', ['filter' => 'login']);
