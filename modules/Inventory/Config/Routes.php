<?php

$routes->add('inventory', '\Modules\inventory\Controllers\inventory::index', ['filter' => 'login']);
$routes->add('inventory/view/(:alphanum)', '\Modules\inventory\Controllers\inventory::view/$1', ['filter' => 'login']);
$routes->add('inventory/reportPdf', '\Modules\inventory\Controllers\inventory::reportPdf', ['filter' => 'login']);
$routes->add('inventory/reportExcel', '\Modules\inventory\Controllers\inventory::reportExcel', ['filter' => 'login']);
