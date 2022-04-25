<?php

$routes->add('summaryconttype', '\Modules\Summaryconttype\Controllers\Summaryconttype::index', ['filter' => 'login']);
$routes->add('summaryconttype/view/(:alphanum)', '\Modules\Summaryconttype\Controllers\Summaryconttype::view/$1', ['filter' => 'login']);
$routes->add('summaryconttype/reportPdf/(:alphanum)/(:any)/(:any)/(:any)', '\Modules\Summaryconttype\Controllers\Summaryconttype::reportPdf/$1/$2/$3/$4', ['filter' => 'login']);
$routes->add('summaryconttype/reportExcel/(:alphanum)/(:any)/(:any)/(:any)', '\Modules\Summaryconttype\Controllers\Summaryconttype::reportExcel/$1/$2/$3/$4', ['filter' => 'login']);
