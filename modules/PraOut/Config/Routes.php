<?php

$routes->add('/praout', '\Modules\PraOut\Controllers\PraOut::index', ['filter' => 'login']);
$routes->add('/praout/list_data', '\Modules\PraOut\Controllers\PraOut::list_data', ['filter' => 'login']);
$routes->add('/praout/view/(:alphanum)', '\Modules\PraOut\Controllers\PraOut::view/$1', ['filter' => 'login']);
$routes->add('/praout/get_list', '\Modules\PraOut\Controllers\PraOut::get_list', ['filter' => 'login']);
$routes->add('/praout/add', '\Modules\PraOut\Controllers\PraOut::add', ['filter' => 'login']);
$routes->add('/praout/get_order_form', '\Modules\PraOut\Controllers\PraOut::get_order_form', ['filter' => 'login']);
$routes->add('/praout/edit/(:alphanum)', '\Modules\PraOut\Controllers\PraOut::edit/$1', ['filter' => 'login']);
$routes->add('/praout/delete/(:alphanum)', '\Modules\PraOut\Controllers\PraOut::delete/$1', ['filter' => 'login']);
// $routes->post('/praout/edit/(:alphanum)', '\Modules\PraOut\Controllers\PraOut::edit/$1', ['filter' => 'login']);
$routes->add('/praout/approve_order/(:alphanum)', '\Modules\PraOut\Controllers\PraOut::approve_order/$1', ['filter' => 'login']);
$routes->add('/praout/appv1_update_container', '\Modules\PraOut\Controllers\PraOut::appv1_update_container', ['filter' => 'login']);
$routes->add('/praout/appv1_containers/(:alphanum)', '\Modules\PraOut\Controllers\PraOut::appv1_containers/$1', ['filter' => 'login']);

$routes->add('/praout/approval2/(:alphanum)', '\Modules\PraOut\Controllers\PraOut::approval2/$1', ['filter' => 'login']);
$routes->add('/praout/proforma/(:alphanum)', '\Modules\PraOut\Controllers\PraOut::proforma/$1', ['filter' => 'login']);
$routes->add('/praout/bukti_bayar', '\Modules\PraOut\Controllers\PraOut::bukti_bayar', ['filter' => 'login']);
$routes->add('/praout/print_order/(:alphanum)', '\Modules\PraOut\Controllers\PraOut::print_order/$1', ['filter' => 'login']);
$routes->add('/praout/print_invoice1/(:alphanum)', '\Modules\PraOut\Controllers\PraOut::print_invoice1/$1', ['filter' => 'login']);
$routes->add('/praout/print_invoice2/(:alphanum)', '\Modules\PraOut\Controllers\PraOut::print_invoice2/$1', ['filter' => 'login']);
// pra container
$routes->add('/praout/addcontainer', '\Modules\PraOut\Controllers\PraOut::addcontainer', ['filter' => 'login']);
$routes->add('/praout/get_container_form', '\Modules\PraOut\Controllers\PraOut::get_container_form', ['filter' => 'login']);
$routes->add('/praout/get_one_container/(:alphanum)', '\Modules\PraOut\Controllers\PraOut::get_one_container/$1', ['filter' => 'login']);
$routes->add('/praout/get_container_by_praid/(:alphanum)', '\Modules\PraOut\Controllers\PraOut::get_container_by_praid/$1', ['filter' => 'login']);
$routes->add('/praout/edit_container', '\Modules\PraOut\Controllers\PraOut::edit_container', ['filter' => 'login']);
$routes->add('/praout/checkContainerNumber', '\Modules\PraOut\Controllers\PraOut::checkContainerNumber', ['filter' => 'login']);
$routes->add('/praout/delete_container/(:alphanum)', '\Modules\PraOut\Controllers\PraOut::delete_container/$1', ['filter' => 'login']);
$routes->add('/praout/final_order/(:alphanum)', '\Modules\PraOut\Controllers\PraOut::final_order/$1', ['filter' => 'login']);
$routes->add('/praout/cetak_kitir/(:alphanum)/(:alphanum)/(:alphanum)', '\Modules\PraOut\Controllers\PraOut::cetak_kitir/$1/$2/$3', ['filter' => 'login']);

// ajax request
$routes->add('/praout/ajax_ccode_listOne/(:alphanum)', '\Modules\PraOut\Controllers\PraOut::ajax_ccode_listOne/$1', ['filter' => 'login']);
$routes->add('/praout/ajax_prcode_listOne/(:alphanum)', '\Modules\PraOut\Controllers\PraOut::ajax_prcode_listOne/$1', ['filter' => 'login']);
$routes->add('/praout/ajax_vessel_listOne/(:any)', '\Modules\PraOut\Controllers\PraOut::ajax_vessel_listOne/$1', ['filter' => 'login']);
$routes->add('/praout/ajax_voyage_list', '\Modules\PraOut\Controllers\PraOut::ajax_voyage_list', ['filter' => 'login']);
$routes->add('/praout/ajax_view/(:alphanum)', '\Modules\PraOut\Controllers\PraOut::ajax_view/$1', ['filter' => 'login']);

// barcode_generator
$routes->get('/praout/generate_barcode', '\Modules\PraOut\Controllers\PraOut::generate_barcode', ['filter' => 'login']);

