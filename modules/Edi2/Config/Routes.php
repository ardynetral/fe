<?php

$routes->add('edi2', '\Modules\Edi2\Controllers\Edi2::index', ['filter' => 'login']);
$routes->add('edi2/view/(:alphanum)', '\Modules\Edi2\Controllers\Edi2::view/$1', ['filter' => 'login']);
$routes->add('edi2/printEDI', '\Modules\Edi2\Controllers\Edi2::printEDI', ['filter' => 'login']);
