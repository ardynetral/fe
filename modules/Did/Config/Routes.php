<?php

$routes->add('/did', '\Modules\Did\Controllers\Did::index', ['filter' => 'login']);
$routes->add('/did/view/(:alphanum)', '\Modules\Did\Controllers\Did::view/$1', ['filter' => 'login']);
$routes->add('/did/add', '\Modules\Did\Controllers\Did::add', ['filter' => 'login']);
$routes->post('/did/add', '\Modules\Did\Controllers\Did::add', ['filter' => 'login']);
$routes->add('/did/edit/(:alphanum)', '\Modules\Did\Controllers\Did::edit/$1', ['filter' => 'login']);
$routes->post('/did/edit/(:alphanum)', '\Modules\Did\Controllers\Did::edit/$1', ['filter' => 'login']);
$routes->add('/did/delete/(:alphanum)', '\Modules\Did\Controllers\Did::delete/$1', ['filter' => 'login']);
$routes->add('/did/ajax_country', '\Modules\Did\Controllers\Did::ajax_country', ['filter' => 'login']);
$routes->add('/did/reportPdf', '\Modules\Did\Controllers\Did::reportPdf', ['filter' => 'login']);
$routes->add('/did/reportExcel', '\Modules\Did\Controllers\Did::reportExcel', ['filter' => 'login']);
// $routes->add('/dailymovementout/ajax_customer', '\Modules\Principal\Controllers\Principal::ajax_country', ['filter' => 'login']);
