<?php

$routes->add('/others', '\Modules\Others\Controllers\Others::index', ['filter' => 'login']);
$routes->add('/others/list_data', '\Modules\Others\Controllers\Others::list_data', ['filter' => 'login']);
$routes->add('/others/view/(:alphanum)', '\Modules\Others\Controllers\Others::view/$1', ['filter' => 'login']);
$routes->add('/others/add', '\Modules\Others\Controllers\Others::add', ['filter' => 'login']);
$routes->post('/others/add', '\Modules\Others\Controllers\Others::add', ['filter' => 'login']);
$routes->add('/others/edit/(:alphanum)', '\Modules\Others\Controllers\Others::edit/$1', ['filter' => 'login']);
$routes->post('/others/edit/(:alphanum)', '\Modules\Others\Controllers\Others::edit/$1', ['filter' => 'login']);
$routes->add('/others/delete/(:alphanum)', '\Modules\Others\Controllers\Others::delete/$1', ['filter' => 'login']);
$routes->add('/others/ajax_country', '\Modules\Others\Controllers\Others::ajax_country', ['filter' => 'login']);
