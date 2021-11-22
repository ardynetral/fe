<?php

$routes->add('/user', '\Modules\User\Controllers\User::index', ['filter' => 'login']);
$routes->add('/user/view/(:alphanum)', '\Modules\User\Controllers\User::view/$1', ['filter' => 'login']);
$routes->add('/user/add', '\Modules\User\Controllers\User::add', ['filter' => 'login']);
$routes->post('/user/add', '\Modules\User\Controllers\User::add', ['filter' => 'login']);
$routes->add('/user/edit/(:alphanum)', '\Modules\User\Controllers\User::edit/$1', ['filter' => 'login']);
$routes->post('/user/edit/(:alphanum)', '\Modules\User\Controllers\User::edit/$1', ['filter' => 'login']);
$routes->add('/user/delete/(:alphanum)', '\Modules\User\Controllers\User::delete/$1', ['filter' => 'login']);
$routes->add('/user/send_email/(:alphanum)', '\Modules\User\Controllers\User::send_email/$1', ['filter' => 'login']);
$routes->add('/user/ajax_pr_dropdown', '\Modules\User\Controllers\User::ajax_pr_dropdown', ['filter' => 'login']);