<?php

$routes->add('losc', '\Modules\Losc\Controllers\Losc::index', ['filter' => 'login']);
$routes->add('losc/view/(:alphanum)', '\Modules\Losc\Controllers\Losc::view/$1', ['filter' => 'login']);
$routes->add('losc/reportPdf', '\Modules\Losc\Controllers\Losc::reportPdf', ['filter' => 'login']);
$routes->add('losc/reportExcel', '\Modules\Losc\Controllers\Losc::reportExcel', ['filter' => 'login']);
