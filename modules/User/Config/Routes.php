<?php

$routes->add('/users', '\Modules\User\Controllers\User::index', ['filter' => 'login']);
$routes->add('/users/view/(:alphanum)', '\Modules\User\Controllers\User::view/$1', ['filter' => 'login']);
$routes->add('/users/add', '\Modules\User\Controllers\User::add', ['filter' => 'login']);
$routes->post('/users/add', '\Modules\User\Controllers\User::add', ['filter' => 'login']);
$routes->add('/users/edit/(:alphanum)', '\Modules\User\Controllers\User::edit/$1', ['filter' => 'login']);
$routes->post('/users/edit/(:alphanum)', '\Modules\User\Controllers\User::edit/$1', ['filter' => 'login']);
$routes->add('/users/delete/(:alphanum)', '\Modules\User\Controllers\User::delete/$1', ['filter' => 'login']);
$routes->add('/users/send_email/(:alphanum)', '\Modules\User\Controllers\User::send_email/$1', ['filter' => 'login']);
$routes->add('/users/ajax_pr_dropdown', '\Modules\User\Controllers\User::ajax_pr_dropdown', ['filter' => 'login']);
$routes->add('/users/ajax_debitur_dropdown', '\Modules\User\Controllers\User::ajax_debitur_dropdown', ['filter' => 'login']);