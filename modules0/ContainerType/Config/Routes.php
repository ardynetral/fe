<?php

$routes->add('/ctype', '\Modules\ContainerType\Controllers\ContainerType::index', ['filter' => 'login']);
$routes->add('/ctype/ajax_ctype', '\Modules\ContainerType\Controllers\ContainerType::ajax_ctype', ['filter' => 'login']);
$routes->add('/ctype/view/(:alphanum)', '\Modules\ContainerType\Controllers\ContainerType::view/$1', ['filter' => 'login']);
$routes->add('/ctype/add', '\Modules\ContainerType\Controllers\ContainerType::add', ['filter' => 'login']);
$routes->post('/ctype/add', '\Modules\ContainerType\Controllers\ContainerType::add', ['filter' => 'login']);
$routes->add('/ctype/edit/(:alphanum)', '\Modules\ContainerType\Controllers\ContainerType::edit/$1', ['filter' => 'login']);
$routes->post('/ctype/edit/(:alphanum)', '\Modules\ContainerType\Controllers\ContainerType::edit/$1', ['filter' => 'login']);
$routes->add('/ctype/delete/(:alphanum)', '\Modules\ContainerType\Controllers\ContainerType::delete/$1', ['filter' => 'login']);