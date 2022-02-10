<?php

$routes->add('inventory', '\Modules\Inventory\Controllers\Inventory::index', ['filter' => 'login']);
$routes->add('inventory/view/(:alphanum)', '\Modules\Inventory\Controllers\Inventory::view/$1', ['filter' => 'login']);
$routes->add('inventory/reportPdf', '\Modules\Inventory\Controllers\Inventory::reportPdf', ['filter' => 'login']);
$routes->add('inventory/reportExcel', '\Modules\Inventory\Controllers\Inventory::reportExcel', ['filter' => 'login']);
