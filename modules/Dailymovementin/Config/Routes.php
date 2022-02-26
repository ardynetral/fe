<?php

$routes->add('dailymovementin', '\Modules\Dailymovementin\Controllers\Dailymovementin::index', ['filter' => 'login']);

//$routes->add('dailymovementin/reportPdf/(:any)/(:any)/(:any)/(:any)/(:any)', '\Modules\Dailymovementin\Controllers\Dailymovementin::reportPdf/$1/$2/$3/$4/$5', ['filter' => 'login']);
//$routes->add('dailymovementin/reportExcel/(:any)/(:any)/(:any)/(:any)/(:any)', '\Modules\Dailymovementin\Controllers\Dailymovementin::reportExcel/$1/$2/$3/$4/$5', ['filter' => 'login']);

$routes->add('dailymovementin/reportPdf/(:any)/(:any)', '\Modules\Dailymovementin\Controllers\Dailymovementin::reportPdf/$1/$2', ['filter' => 'login']);
$routes->add('dailymovementin/reportExcel/(:any)/(:any)', '\Modules\Dailymovementin\Controllers\Dailymovementin::reportExcel/$1/$2', ['filter' => 'login']);
