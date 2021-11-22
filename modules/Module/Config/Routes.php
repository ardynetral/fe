<?php

$routes->add('/module', '\Modules\Module\Controllers\Module::index', ['filter' => 'login']);
$routes->add('/module/view/(:alphanum)', '\Modules\Module\Controllers\Module::view/$1', ['filter' => 'login']);
$routes->add('/module/add', '\Modules\Module\Controllers\Module::add', ['filter' => 'login']);
$routes->post('/module/add', '\Modules\Module\Controllers\Module::add', ['filter' => 'login']);
$routes->add('/module/edit/(:alphanum)', '\Modules\Module\Controllers\Module::edit/$1', ['filter' => 'login']);
$routes->post('/module/edit/(:alphanum)', '\Modules\Module\Controllers\Module::edit/$1', ['filter' => 'login']);
$routes->add('/module/listmodules', '\Modules\Module\Controllers\Module::listmodule/$1', ['filter' => 'login']);
