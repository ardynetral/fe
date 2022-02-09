<?php

$routes->add('/did', '\Modules\Did\Controllers\Did::index', ['filter' => 'login']);
$routes->add('/did/view/(:alphanum)', '\Modules\Did\Controllers\Did::view/$1', ['filter' => 'login']);
$routes->add('/did/reportPdf', '\Modules\Did\Controllers\Did::reportPdf', ['filter' => 'login']);
$routes->add('/did/reportExcel', '\Modules\Did\Controllers\Did::reportExcel', ['filter' => 'login']);
