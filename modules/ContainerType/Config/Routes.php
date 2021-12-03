<?php

$routes->add('/containertype', '\Modules\ContainerType\Controllers\ContainerType::index', ['filter' => 'login']);
$routes->add('/containertype/ajax_ctype', '\Modules\ContainerType\Controllers\ContainerType::ajax_ctype', ['filter' => 'login']);
$routes->add('/containertype/view/(:alphanum)', '\Modules\ContainerType\Controllers\ContainerType::view/$1', ['filter' => 'login']);
$routes->add('/containertype/add', '\Modules\ContainerType\Controllers\ContainerType::add', ['filter' => 'login']);
$routes->post('/containertype/add', '\Modules\ContainerType\Controllers\ContainerType::add', ['filter' => 'login']);
$routes->add('/containertype/edit/(:alphanum)', '\Modules\ContainerType\Controllers\ContainerType::edit/$1', ['filter' => 'login']);
$routes->post('/containertype/edit/(:alphanum)', '\Modules\ContainerType\Controllers\ContainerType::edit/$1', ['filter' => 'login']);
$routes->add('/containertype/delete/(:alphanum)', '\Modules\ContainerType\Controllers\ContainerType::delete/$1', ['filter' => 'login']);
$routes->add('/containertype/list_data', '\Modules\ContainerType\Controllers\ContainerType::list_data', ['filter' => 'login']);