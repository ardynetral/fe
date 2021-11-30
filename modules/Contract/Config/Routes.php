<?php

$routes->add('/contract', '\Modules\Contract\Controllers\Contract::index', ['filter' => 'login']);
$routes->add('/contract/list_data', '\Modules\Contract\Controllers\Contract::list_data', ['filter' => 'login']);
$routes->add('/contract/view/(:alphanum)', '\Modules\Contract\Controllers\Contract::view/$1', ['filter' => 'login']);
$routes->add('/contract/add', '\Modules\Contract\Controllers\Contract::add', ['filter' => 'login']);
$routes->post('/contract/add', '\Modules\Contract\Controllers\Contract::add', ['filter' => 'login']);
$routes->add('/contract/edit/(:alphanum)', '\Modules\Contract\Controllers\Contract::edit/$1', ['filter' => 'login']);
$routes->post('/contract/edit/(:alphanum)', '\Modules\Contract\Controllers\Contract::edit/$1', ['filter' => 'login']);
$routes->add('/contract/delete/(:alphanum)', '\Modules\Contract\Controllers\Contract::delete/$1', ['filter' => 'login']);
$routes->add('/contract/ajax_country', '\Modules\Contract\Controllers\Contract::ajax_country', ['filter' => 'login']);
