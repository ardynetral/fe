<?php
// $routes->get('/create_user', '\Modules\Auth\Controllers\Auth::create_user', ['filter' => 'login']);
// $routes->post('/set_user', '\Modules\Auth\Controllers\Auth::set_user');
$routes->get('/login', '\Modules\Auth\Controllers\Auth::login');
$routes->post('/set_login', '\Modules\Auth\Controllers\Auth::set_login');
// $routes->get('/register', '\Modules\Auth\Controllers\Auth::register');
$routes->get('/user_profile', '\Modules\Auth\Controllers\Auth::user_profile', ['filter' => 'login']);
$routes->get('/forgot_password', '\Modules\Auth\Controllers\Auth::forgot_password');
$routes->add('/activate', '\Modules\Auth\Controllers\Auth::activate');
$routes->add('/change_password', '\Modules\Auth\Controllers\Auth::change_password');
$routes->get('/logout', '\Modules\Auth\Controllers\Auth::logout');