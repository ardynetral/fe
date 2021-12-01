<?php

$routes->add('/approval', '\Modules\Approval\Controllers\Approval::index', ['filter' => 'login']);
$routes->add('/approval/add', '\Modules\Approval\Controllers\Approval::add', ['filter' => 'login']);
// $routes->add('/prain/view/(:alphanum)', '\Modules\PraIn\Controllers\PraIn::view/$1', ['filter' => 'login']);
// $routes->add('/prain/add', '\Modules\PraIn\Controllers\PraIn::add', ['filter' => 'login']);
// $routes->add('/prain/edit/(:alphanum)', '\Modules\PraIn\Controllers\PraIn::edit/$1', ['filter' => 'login']);
// $routes->add('/prain/delete/(:alphanum)', '\Modules\PraIn\Controllers\PraIn::delete/$1', ['filter' => 'login']);
$routes->add('/approval/list_data', '\Modules\Approval\Controllers\Approval::list_data', ['filter' => 'login']);
