<?php

$routes->add('/dim', '\Modules\Dim\Controllers\Dim::index', ['filter' => 'login']);
$routes->add('/dim/view/(:alphanum)', '\Modules\Dim\Controllers\Dim::view/$1', ['filter' => 'login']);
$routes->add('/dim/reportPdf', '\Modules\Dim\Controllers\Dim::reportPdf', ['filter' => 'login']);
$routes->add('/dim/reportExcel', '\Modules\Dim\Controllers\Dim::reportExcel', ['filter' => 'login']);
