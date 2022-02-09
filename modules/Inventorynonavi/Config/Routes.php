<?php

$routes->add('inventorynonavi', '\Modules\inventorynonavi\Controllers\inventorynonavi::index', ['filter' => 'login']);
$routes->add('inventorynonavi/view/(:alphanum)', '\Modules\inventorynonavi\Controllers\inventorynonavi::view/$1', ['filter' => 'login']);
$routes->add('inventorynonavi/reportPdf', '\Modules\inventorynonavi\Controllers\inventorynonavi::reportPdf', ['filter' => 'login']);
$routes->add('inventorynonavi/reportExcel', '\Modules\inventorynonavi\Controllers\inventorynonavi::reportExcel', ['filter' => 'login']);
