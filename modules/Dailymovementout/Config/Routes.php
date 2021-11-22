<?php

$routes->add('/dailymovementout', '\Modules\Dailymovementout\Controllers\Dailymovementout::index', ['filter' => 'login']);
$routes->add('/dailymovementout/view/(:alphanum)', '\Modules\Dailymovementout\Controllers\Dailymovementout::view/$1', ['filter' => 'login']);
$routes->add('/dailymovementout/add', '\Modules\Dailymovementout\Controllers\Dailymovementout::add', ['filter' => 'login']);
$routes->post('/dailymovementout/add', '\Modules\Dailymovementout\Controllers\Dailymovementout::add', ['filter' => 'login']);
$routes->add('/dailymovementout/edit/(:alphanum)', '\Modules\Dailymovementout\Controllers\Dailymovementout::edit/$1', ['filter' => 'login']);
$routes->post('/dailymovementout/edit/(:alphanum)', '\Modules\Dailymovementout\Controllers\Dailymovementout::edit/$1', ['filter' => 'login']);
$routes->add('/dailymovementout/delete/(:alphanum)', '\Modules\Dailymovementout\Controllers\Dailymovementout::delete/$1', ['filter' => 'login']);
$routes->add('/dailymovementout/ajax_country', '\Modules\Dailymovementout\Controllers\Dailymovementout::ajax_country', ['filter' => 'login']);
// $routes->add('/dailymovementout/ajax_customer', '\Modules\Principal\Controllers\Principal::ajax_country', ['filter' => 'login']);
