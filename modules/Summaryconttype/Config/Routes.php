<?php

$routes->add('summaryconttype', '\Modules\Summaryconttype\Controllers\Summaryconttype::index', ['filter' => 'login']);
$routes->add('summaryconttype/view/(:alphanum)', '\Modules\Summaryconttype\Controllers\Summaryconttype::view/$1', ['filter' => 'login']);
$routes->add('summaryconttype/reportPdf', '\Modules\Summaryconttype\Controllers\Summaryconttype::reportPdf', ['filter' => 'login']);
$routes->add('summaryconttype/reportExcel', '\Modules\Summaryconttype\Controllers\Summaryconttype::reportExcel', ['filter' => 'login']);
