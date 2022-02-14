<?php

$routes->add('inventorynonavi', '\Modules\Inventorynonavi\Controllers\Inventorynonavi::index', ['filter' => 'login']);
$routes->add('inventorynonavi/reportPdf', '\Modules\Inventorynonavi\Controllers\Inventorynonavi::reportPdf', ['filter' => 'login']);
$routes->add('inventorynonavi/reportExcel', '\Modules\Inventorynonavi\Controllers\Inventorynonavi::reportExcel', ['filter' => 'login']);
