<?php

$routes->add('/statusreport', '\Modules\Statusreport\Controllers\Statusreport::index', ['filter' => 'login']);
$routes->add('/statusreport/view/(:alphanum)', '\Modules\Statusreport\Controllers\Statusreport::view/$1', ['filter' => 'login']);
$routes->add('/statusreport/add', '\Modules\Statusreport\Controllers\Statusreport::add', ['filter' => 'login']);
$routes->post('/statusreport/add', '\Modules\Statusreport\Controllers\Statusreport::add', ['filter' => 'login']);
$routes->add('/statusreport/edit/(:alphanum)', '\Modules\Statusreport\Controllers\Statusreport::edit/$1', ['filter' => 'login']);
$routes->post('/statusreport/edit/(:alphanum)', '\Modules\Statusreport\Controllers\Statusreport::edit/$1', ['filter' => 'login']);
$routes->add('/statusreport/delete/(:alphanum)', '\Modules\Statusreport\Controllers\Statusreport::delete/$1', ['filter' => 'login']);
$routes->add('/statusreport/ajax_country', '\Modules\Statusreport\Controllers\Statusreport::ajax_country', ['filter' => 'login']);
// $routes->add('/dailymovementout/ajax_customer', '\Modules\Principal\Controllers\Principal::ajax_country', ['filter' => 'login']);
