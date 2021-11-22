<?php

$routes->add('/dim', '\Modules\Dim\Controllers\Dim::index', ['filter' => 'login']);
$routes->add('/dim/view/(:alphanum)', '\Modules\Dim\Controllers\Dim::view/$1', ['filter' => 'login']);
$routes->add('/dim/add', '\Modules\Dim\Controllers\Dim::add', ['filter' => 'login']);
$routes->post('/dim/add', '\Modules\Dim\Controllers\Dim::add', ['filter' => 'login']);
$routes->add('/dim/edit/(:alphanum)', '\Modules\Dim\Controllers\Dim::edit/$1', ['filter' => 'login']);
$routes->post('/dim/edit/(:alphanum)', '\Modules\Dim\Controllers\Dim::edit/$1', ['filter' => 'login']);
$routes->add('/dim/delete/(:alphanum)', '\Modules\Dim\Controllers\Dim::delete/$1', ['filter' => 'login']);
$routes->add('/dim/ajax_country', '\Modules\Dim\Controllers\Dim::ajax_country', ['filter' => 'login']);
// $routes->add('/dailymovementout/ajax_customer', '\Modules\Principal\Controllers\Principal::ajax_country', ['filter' => 'login']);
