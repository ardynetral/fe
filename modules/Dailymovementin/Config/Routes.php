<?php

$routes->add('dailymovementin', '\Modules\Dailymovementin\Controllers\Dailymovementin::index', ['filter' => 'login']);
$routes->add('dailymovementin/view/(:alphanum)', '\Modules\Dailymovementin\Controllers\Dailymovementin::view/$1', ['filter' => 'login']);
$routes->add('dailymovementin/reportPdf', '\Modules\Dailymovementin\Controllers\Dailymovementin::reportPdf', ['filter' => 'login']);
$routes->add('dailymovementin/reportExcel', '\Modules\Dailymovementin\Controllers\Dailymovementin::reportExcel', ['filter' => 'login']);