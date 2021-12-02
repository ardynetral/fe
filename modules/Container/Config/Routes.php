<?php

$routes->add('/container', '\Modules\Container\Controllers\Container::index', ['filter' => 'login']);
$routes->get('/container/p/(:alphanum)', '\Modules\Container\Controllers\Container::index/$1', ['filter' => 'login']);
$routes->add('/container/ajax_container', '\Modules\Container\Controllers\Container::ajax_container', ['filter' => 'login']);
$routes->add('/container/view/(:alphanum)', '\Modules\Container\Controllers\Container::view/$1', ['filter' => 'login']);
$routes->add('/container/add', '\Modules\Container\Controllers\Container::add', ['filter' => 'login']);
$routes->post('/container/add', '\Modules\Container\Controllers\Container::add', ['filter' => 'login']);
$routes->add('/container/edit/(:alphanum)', '\Modules\Container\Controllers\Container::edit/$1', ['filter' => 'login']);
$routes->add('/container/edit/(:alphanum)', '\Modules\Container\Controllers\Container::edit/$1', ['filter' => 'login']);
$routes->add('/container/delete/(:alphanum)', '\Modules\Container\Controllers\Container::delete/$1', ['filter' => 'login']);
$routes->add('/container/ajax_ccode/(:alphanum)', '\Modules\Container\Controllers\Container::ajax_ccode/$1', ['filter' => 'login']);
$routes->add('/container/list_data', '\Modules\Container\Controllers\Container::list_data', ['filter' => 'login']);
$routes->add('/container/checkContainerNumber', '\Modules\Container\Controllers\Container::checkContainerNumber', ['filter' => 'login']);
