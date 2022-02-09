<?php

$routes->add('edi3', '\Modules\Edi3\Controllers\Edi3::index', ['filter' => 'login']);
$routes->add('edi3/view/(:alphanum)', '\Modules\Edi3\Controllers\Edi3::view/$1', ['filter' => 'login']);
$routes->add('edi3/printEDI', '\Modules\Edi3\Controllers\Edi3::printEDI', ['filter' => 'login']);
