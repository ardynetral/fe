<?php

$routes->add('/billrepair', '\Modules\BillRepair\Controllers\BillRepair::index', ['filter' => 'login']);
$routes->add('billrepair/reportPdf', '\Modules\BillRepair\Controllers\BillRepair::reportPdf', ['filter' => 'login']);
$routes->add('billrepair/reportExcel', '\Modules\BillRepair\Controllers\BillRepair::reportExcel', ['filter' => 'login']);
// $routes->add('/prain/view/(:alphanum)', '\Modules\PraIn\Controllers\PraIn::view/$1', ['filter' => 'login']);
// $routes->add('/prain/add', '\Modules\PraIn\Controllers\PraIn::add', ['filter' => 'login']);
// $routes->add('/prain/edit/(:alphanum)', '\Modules\PraIn\Controllers\PraIn::edit/$1', ['filter' => 'login']);
// $routes->add('/prain/delete/(:alphanum)', '\Modules\PraIn\Controllers\PraIn::delete/$1', ['filter' => 'login']);
