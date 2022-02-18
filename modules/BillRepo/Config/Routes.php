<?php

$routes->add('/billrepo', '\Modules\BillRepo\Controllers\BillRepo::index', ['filter' => 'login']);
$routes->add('billrepo/reportPdf/(:alphanum)/(:alphanum)/(:alphanum)/(:alphanum)/(:alphanum)/(:alphanum)/(:alphanum)/(:alphanum)', '\Modules\BillRepo\Controllers\BillRepo::reportPdf//$1/$2/$3/$4/$5/$6/$7/$8', ['filter' => 'login']);
$routes->add('billrepo/reportExcel', '\Modules\BillRepo\Controllers\BillRepo::reportExcel', ['filter' => 'login']);

