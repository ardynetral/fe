<?php

$routes->add('rptreceipt', '\Modules\RptReceipt\Controllers\RptReceipt::index', ['filter' => 'login']);
$routes->add('rptreceipt/reportPdf/(:any)/(:any)', '\Modules\RptReceipt\Controllers\RptReceipt::reportPdf/$1/$2', ['filter' => 'login']);
$routes->add('rptreceipt/reportExcel/(:any)/(:any)', '\Modules\RptReceipt\Controllers\RptReceipt::reportExcel/$1/$2', ['filter' => 'login']);
