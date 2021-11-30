<?php

$routes->add('/forwading', '\Modules\Forwading\Controllers\Forwading::index', ['filter' => 'login']);
$routes->add('/forwading/list_data', '\Modules\Forwading\Controllers\Forwading::list_data', ['filter' => 'login']);
$routes->add('/forwading/view/(:alphanum)', '\Modules\Forwading\Controllers\Forwading::view/$1', ['filter' => 'login']);
$routes->add('/forwading/add', '\Modules\Forwading\Controllers\Forwading::add', ['filter' => 'login']);
$routes->post('/forwading/add', '\Modules\Forwading\Controllers\Forwading::add', ['filter' => 'login']);
$routes->add('/forwading/edit/(:alphanum)', '\Modules\Forwading\Controllers\Forwading::edit/$1', ['filter' => 'login']);
$routes->post('/forwading/edit/(:alphanum)', '\Modules\Forwading\Controllers\Forwading::edit/$1', ['filter' => 'login']);
$routes->add('/forwading/delete/(:alphanum)', '\Modules\Forwading\Controllers\Forwading::delete/$1', ['filter' => 'login']);
$routes->add('/forwading/ajax_country', '\Modules\Forwading\Controllers\Forwading::ajax_country', ['filter' => 'login']);
