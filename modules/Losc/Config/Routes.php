<?php

$routes->add('losc', '\Modules\Losc\Controllers\Losc::index', ['filter' => 'login']);
$routes->add('losc/reportPdf/(:alphanum)/(:any)/(:alphanum)/(:alphanum)/(:alphanum)/(:alphanum)', '\Modules\Losc\Controllers\Losc::reportPdf/$1/$2/$3/$4/$5/$6', ['filter' => 'login']);
$routes->add('losc/reportExcel/(:alphanum)/(:any)/(:alphanum)/(:alphanum)/(:alphanum)/(:alphanum)', '\Modules\Losc\Controllers\Losc::reportExcel/$1/$2/$3/$4/$5/$6', ['filter' => 'login']);
