<?php

$routes->add('/prain', '\Modules\PraIn\Controllers\PraIn::index', ['filter' => 'login']);
$routes->add('/prain/view/(:alphanum)', '\Modules\PraIn\Controllers\PraIn::view/$1', ['filter' => 'login']);
$routes->add('/prain/get_list', '\Modules\PraIn\Controllers\PraIn::get_list', ['filter' => 'login']);
$routes->add('/prain/add', '\Modules\PraIn\Controllers\PraIn::add', ['filter' => 'login']);
$routes->add('/prain/get_order_form', '\Modules\PraIn\Controllers\PraIn::get_order_form', ['filter' => 'login']);
$routes->add('/prain/edit/(:alphanum)', '\Modules\PraIn\Controllers\PraIn::edit/$1', ['filter' => 'login']);
$routes->add('/prain/delete/(:alphanum)', '\Modules\PraIn\Controllers\PraIn::delete/$1', ['filter' => 'login']);
// $routes->post('/prain/edit/(:alphanum)', '\Modules\PraIn\Controllers\PraIn::edit/$1', ['filter' => 'login']);
$routes->add('/prain/print_order/(:alphanum)', '\Modules\PraIn\Controllers\PraIn::print_order/$1', ['filter' => 'login']);
$routes->add('/prain/approve_order/(:alphanum)', '\Modules\PraIn\Controllers\PraIn::approve_order/$1', ['filter' => 'login']);
$routes->add('/prain/proforma/(:alphanum)', '\Modules\PraIn\Controllers\PraIn::proforma/$1', ['filter' => 'login']);

// pra container
$routes->add('/prain/addcontainer', '\Modules\PraIn\Controllers\PraIn::addcontainer', ['filter' => 'login']);
$routes->add('/prain/get_container_form', '\Modules\PraIn\Controllers\PraIn::get_container_form', ['filter' => 'login']);
$routes->add('/prain/get_one_container/(:alphanum)', '\Modules\PraIn\Controllers\PraIn::get_one_container/$1', ['filter' => 'login']);
$routes->add('/prain/edit_container', '\Modules\PraIn\Controllers\PraIn::edit_container', ['filter' => 'login']);
$routes->add('/prain/checkContainerNumber', '\Modules\PraIn\Controllers\PraIn::checkContainerNumber', ['filter' => 'login']);
$routes->add('/prain/delete_container/(:alphanum)', '\Modules\PraIn\Controllers\PraIn::delete_container/$1', ['filter' => 'login']);

// ajax request
$routes->add('/prain/ajax_ccode_listOne/(:alphanum)', '\Modules\PraIn\Controllers\PraIn::ajax_ccode_listOne/$1', ['filter' => 'login']);
$routes->add('/prain/ajax_prcode_listOne/(:alphanum)', '\Modules\PraIn\Controllers\PraIn::ajax_prcode_listOne/$1', ['filter' => 'login']);
$routes->add('/prain/ajax_vessel_listOne/(:any)', '\Modules\PraIn\Controllers\PraIn::ajax_vessel_listOne/$1', ['filter' => 'login']);
$routes->add('/prain/ajax_voyage_list', '\Modules\PraIn\Controllers\PraIn::ajax_voyage_list', ['filter' => 'login']);
$routes->add('/prain/ajax_view/(:alphanum)', '\Modules\PraIn\Controllers\PraIn::ajax_view/$1', ['filter' => 'login']);