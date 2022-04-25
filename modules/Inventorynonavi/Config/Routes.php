<?php

$routes->add('inventorynonavi', '\Modules\Inventorynonavi\Controllers\Inventorynonavi::index', ['filter' => 'login']);
$routes->add('inventorynonavi/reportPdf/(:alphanum)/(:alphanum)/(:alphanum)/(:alphanum)', '\Modules\Inventorynonavi\Controllers\Inventorynonavi::reportPdf/$1/$2/$3/$4', ['filter' => 'login']);
$routes->add('inventorynonavi/reportExcel/(:alphanum)/(:alphanum)/(:alphanum)/(:alphanum)', '\Modules\Inventorynonavi\Controllers\Inventorynonavi::reportExcel/$1/$2/$3/$4', ['filter' => 'login']);
