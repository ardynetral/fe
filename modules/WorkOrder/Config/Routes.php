<?php

$routes->add('/wo', '\Modules\WorkOrder\Controllers\WorkOrder::index', ['filter' => 'login']);
$routes->add('/wo/add', '\Modules\WorkOrder\Controllers\WorkOrder::add', ['filter' => 'login']);
// $routes->add('/prain/view/(:alphanum)', '\Modules\PraIn\Controllers\PraIn::view/$1', ['filter' => 'login']);
// $routes->add('/prain/add', '\Modules\PraIn\Controllers\PraIn::add', ['filter' => 'login']);
// $routes->add('/prain/edit/(:alphanum)', '\Modules\PraIn\Controllers\PraIn::edit/$1', ['filter' => 'login']);
// $routes->add('/prain/delete/(:alphanum)', '\Modules\PraIn\Controllers\PraIn::delete/$1', ['filter' => 'login']);
$routes->add('/wo/list_data', '\Modules\WorkOrder\Controllers\WorkOrder::list_data', ['filter' => 'login']);