<?php

$routes->add('/survey', '\Modules\Survey\Controllers\Survey::index', ['filter' => 'login']);
$routes->add('/survey/add', '\Modules\Survey\Controllers\Survey::add', ['filter' => 'login']);
// $routes->add('/prain/view/(:alphanum)', '\Modules\PraIn\Controllers\PraIn::view/$1', ['filter' => 'login']);
// $routes->add('/prain/add', '\Modules\PraIn\Controllers\PraIn::add', ['filter' => 'login']);
// $routes->add('/prain/edit/(:alphanum)', '\Modules\PraIn\Controllers\PraIn::edit/$1', ['filter' => 'login']);
// $routes->add('/prain/delete/(:alphanum)', '\Modules\PraIn\Controllers\PraIn::delete/$1', ['filter' => 'login']);
$routes->add('/survey/list_data', '\Modules\Survey\Controllers\Survey::list_data', ['filter' => 'login']);
