<?php

$routes->add('inventory', '\Modules\Inventory\Controllers\Inventory::index', ['filter' => 'login']);
$routes->add('inventory/view/(:alphanum)', '\Modules\Inventory\Controllers\Inventory::view/$1', ['filter' => 'login']);
$routes->add('inventory/reportPdf/(:alphanum)/(:alphanum)/(:alphanum)/(:alphanum)', '\Modules\Inventory\Controllers\Inventory::reportPdf/$1/$2/$3/$4', ['filter' => 'login']);
$routes->add('inventory/reportExcel/(:alphanum)/(:alphanum)/(:alphanum)/(:alphanum)', '\Modules\Inventory\Controllers\Inventory::reportExcel/$1/$2/$3/$4', ['filter' => 'login']);
