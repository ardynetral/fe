<?php

$routes->add('/survey', '\Modules\Survey\Controllers\Survey::index', ['filter' => 'login']);
$routes->get('/survey/add', '\Modules\Survey\Controllers\Survey::add', ['filter' => 'login']);
$routes->get('/survey/add/(:alphanum)', '\Modules\Survey\Controllers\Survey::add/$1', ['filter' => 'login']);
$routes->add('/survey/view/(:alphanum)', '\Modules\Survey\Controllers\Survey::view/$1', ['filter' => 'login']);
$routes->add('/survey/save', '\Modules\Survey\Controllers\Survey::save', ['filter' => 'login']);
$routes->add('/survey/delete/(:alphanum)/(:alphanum)', '\Modules\Survey\Controllers\Survey::delete/$1/$2', ['filter' => 'login']);
$routes->add('/survey/list_data', '\Modules\Survey\Controllers\Survey::list_data', ['filter' => 'login']);
$routes->add('/survey/checkValid', '\Modules\Survey\Controllers\Survey::checkValid', ['filter' => 'login']);
