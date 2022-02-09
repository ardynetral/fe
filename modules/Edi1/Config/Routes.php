<?php

$routes->add('edi1', '\Modules\Edi1\Controllers\Edi1::index', ['filter' => 'login']);
$routes->add('edi1/view/(:alphanum)', '\Modules\Edi1\Controllers\Edi1::view/$1', ['filter' => 'login']);
$routes->add('edi1/printEDI', '\Modules\Edi1\Controllers\Edi1::printEDI', ['filter' => 'login']);
