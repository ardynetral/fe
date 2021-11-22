<?php

$routes->add('/damageprogress', '\Modules\Damageprogress\Controllers\Damageprogress::index', ['filter' => 'login']);
$routes->add('/damageprogress/view/(:alphanum)', '\Modules\Damageprogress\Controllers\Damageprogress::view/$1', ['filter' => 'login']);
$routes->add('/damageprogress/add', '\Modules\Damageprogress\Controllers\Damageprogress::add', ['filter' => 'login']);
$routes->post('/damageprogress/add', '\Modules\Damageprogress\Controllers\Damageprogress::add', ['filter' => 'login']);
$routes->add('/damageprogress/edit/(:alphanum)', '\Modules\Damageprogress\Controllers\Damageprogress::edit/$1', ['filter' => 'login']);
$routes->post('/damageprogress/edit/(:alphanum)', '\Modules\Damageprogress\Controllers\Damageprogress::edit/$1', ['filter' => 'login']);
$routes->add('/damageprogress/delete/(:alphanum)', '\Modules\Damageprogress\Controllers\Damageprogress::delete/$1', ['filter' => 'login']);
$routes->add('/damageprogress/ajax_country', '\Modules\Damageprogress\Controllers\Damageprogress::ajax_country', ['filter' => 'login']);
// $routes->add('/dailymovementout/ajax_customer', '\Modules\Principal\Controllers\Principal::ajax_country', ['filter' => 'login']);
