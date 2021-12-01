<?php

$routes->add('/rip', '\Modules\RepairInProgress\Controllers\RepairInProgress::index', ['filter' => 'login']);
$routes->add('/rip/add', '\Modules\RepairInProgress\Controllers\RepairInProgress::add', ['filter' => 'login']);
// $routes->add('/prain/view/(:alphanum)', '\Modules\PraIn\Controllers\PraIn::view/$1', ['filter' => 'login']);
// $routes->add('/prain/add', '\Modules\PraIn\Controllers\PraIn::add', ['filter' => 'login']);
// $routes->add('/prain/edit/(:alphanum)', '\Modules\PraIn\Controllers\PraIn::edit/$1', ['filter' => 'login']);
// $routes->add('/prain/delete/(:alphanum)', '\Modules\PraIn\Controllers\PraIn::delete/$1', ['filter' => 'login']);
$routes->add('/rip/list_data', '\Modules\RepairInProgress\Controllers\RepairInProgress::list_data', ['filter' => 'login']);
