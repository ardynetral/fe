<?php

$routes->add('/summaryconttype', '\Modules\Summaryconttype\Controllers\Summaryconttype::index', ['filter' => 'login']);
$routes->add('/summaryconttype/view/(:alphanum)', '\Modules\Summaryconttype\Controllers\Summaryconttype::view/$1', ['filter' => 'login']);
$routes->add('/summaryconttype/add', '\Modules\Summaryconttype\Controllers\Summaryconttype::add', ['filter' => 'login']);
$routes->post('/summaryconttype/add', '\Modules\Summaryconttype\Controllers\Summaryconttype::add', ['filter' => 'login']);
$routes->add('/summaryconttype/edit/(:alphanum)', '\Modules\Summaryconttype\Controllers\Summaryconttype::edit/$1', ['filter' => 'login']);
$routes->post('/summaryconttype/edit/(:alphanum)', '\Modules\Summaryconttype\Controllers\Summaryconttype::edit/$1', ['filter' => 'login']);
$routes->add('/summaryconttype/delete/(:alphanum)', '\Modules\Summaryconttype\Controllers\Summaryconttype::delete/$1', ['filter' => 'login']);
$routes->add('/summaryconttype/ajax_country', '\Modules\Summaryconttype\Controllers\Summaryconttype::ajax_country', ['filter' => 'login']);
// $routes->add('/dailymovementout/ajax_customer', '\Modules\Principal\Controllers\Principal::ajax_country', ['filter' => 'login']);
