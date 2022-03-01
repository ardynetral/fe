<?php

$routes->add('securityreport', '\Modules\SecurityReport\Controllers\SecurityReport::index', ['filter' => 'login']);

//$routes->add('dailymovementin/reportPdf/(:any)/(:any)/(:any)/(:any)/(:any)', '\Modules\Dailymovementin\Controllers\Dailymovementin::reportPdf/$1/$2/$3/$4/$5', ['filter' => 'login']);
//$routes->add('dailymovementin/reportExcel/(:any)/(:any)/(:any)/(:any)/(:any)', '\Modules\Dailymovementin\Controllers\Dailymovementin::reportExcel/$1/$2/$3/$4/$5', ['filter' => 'login']);

$routes->add('securityreport/reportPdf/(:any)/(:any)', '\Modules\SecurityReport\Controllers\SecurityReport::reportPdf/$1/$2', ['filter' => 'login']);
$routes->add('securityreport/reportExcel/(:any)/(:any)', '\Modules\SecurityReport\Controllers\SecurityReport::reportExcel/$1/$2', ['filter' => 'login']);
