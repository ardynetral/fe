<?php

$routes->add('/changecontainer', '\Modules\ChangeContainer\Controllers\ChangeContainer::index', ['filter' => 'login']);
$routes->add('/changecontainer/list_data', '\Modules\ChangeContainer\Controllers\ChangeContainer::list_data', ['filter' => 'login']);
$routes->add('/changecontainer/view/(:alphanum)', '\Modules\ChangeContainer\Controllers\ChangeContainer::view/$1', ['filter' => 'login']);
$routes->add('/changecontainer/get_list', '\Modules\ChangeContainer\Controllers\ChangeContainer::get_list', ['filter' => 'login']);
$routes->add('/changecontainer/add', '\Modules\ChangeContainer\Controllers\ChangeContainer::add', ['filter' => 'login']);
$routes->add('/changecontainer/get_order_form', '\Modules\ChangeContainer\Controllers\ChangeContainer::get_order_form', ['filter' => 'login']);
$routes->add('/changecontainer/edit/(:alphanum)', '\Modules\ChangeContainer\Controllers\ChangeContainer::edit/$1', ['filter' => 'login']);
$routes->add('/changecontainer/delete/(:alphanum)', '\Modules\ChangeContainer\Controllers\ChangeContainer::delete/$1', ['filter' => 'login']);
$routes->add('/changecontainer/approve_order/(:alphanum)', '\Modules\ChangeContainer\Controllers\ChangeContainer::approve_order/$1', ['filter' => 'login']);
$routes->add('/changecontainer/appv1_update_container', '\Modules\ChangeContainer\Controllers\ChangeContainer::appv1_update_container', ['filter' => 'login']);
$routes->add('/changecontainer/appv1_containers/(:alphanum)', '\Modules\ChangeContainer\Controllers\ChangeContainer::appv1_containers/$1', ['filter' => 'login']);

$routes->add('/changecontainer/approval2/(:alphanum)', '\Modules\ChangeContainer\Controllers\ChangeContainer::approval2/$1', ['filter' => 'login']);
$routes->add('/changecontainer/proforma/(:alphanum)', '\Modules\ChangeContainer\Controllers\ChangeContainer::proforma/$1', ['filter' => 'login']);
$routes->add('/changecontainer/bukti_bayar', '\Modules\ChangeContainer\Controllers\ChangeContainer::bukti_bayar', ['filter' => 'login']);
$routes->add('/changecontainer/print_order/(:alphanum)', '\Modules\ChangeContainer\Controllers\ChangeContainer::print_order/$1', ['filter' => 'login']);
$routes->add('/changecontainer/print_proforma/(:alphanum)', '\Modules\ChangeContainer\Controllers\ChangeContainer::print_proforma/$1', ['filter' => 'login']);
$routes->add('/changecontainer/print_invoice1/(:alphanum)', '\Modules\ChangeContainer\Controllers\ChangeContainer::print_invoice1/$1', ['filter' => 'login']);
$routes->add('/changecontainer/print_invoice2/(:alphanum)', '\Modules\ChangeContainer\Controllers\ChangeContainer::print_invoice2/$1', ['filter' => 'login']);
$routes->add('/changecontainer/final_order/(:alphanum)', '\Modules\ChangeContainer\Controllers\ChangeContainer::final_order/$1', ['filter' => 'login']);
$routes->add('/changecontainer/cetak_kitir/(:alphanum)/(:alphanum)/(:alphanum)', '\Modules\ChangeContainer\Controllers\ChangeContainer::cetak_kitir/$1/$2/$3', ['filter' => 'login']);

// pra container
$routes->add('/changecontainer/addcontainer', '\Modules\ChangeContainer\Controllers\ChangeContainer::addcontainer', ['filter' => 'login']);
$routes->add('/changecontainer/get_container_form', '\Modules\ChangeContainer\Controllers\ChangeContainer::get_container_form', ['filter' => 'login']);
$routes->add('/changecontainer/get_one_container/(:alphanum)', '\Modules\ChangeContainer\Controllers\ChangeContainer::get_one_container/$1', ['filter' => 'login']);
$routes->add('/changecontainer/get_container_by_praid/(:alphanum)', '\Modules\ChangeContainer\Controllers\ChangeContainer::get_container_by_praid/$1', ['filter' => 'login']);
$routes->add('/changecontainer/change_container', '\Modules\ChangeContainer\Controllers\ChangeContainer::change_container', ['filter' => 'login']);
$routes->add('/changecontainer/edit_get_container/(:alphanum)', '\Modules\ChangeContainer\Controllers\ChangeContainer::edit_get_container/$1', ['filter' => 'login']);
$routes->add('/changecontainer/checkContainerNumber', '\Modules\ChangeContainer\Controllers\ChangeContainer::checkContainerNumber', ['filter' => 'login']);
$routes->add('/changecontainer/delete_container/(:alphanum)', '\Modules\ChangeContainer\Controllers\ChangeContainer::delete_container/$1', ['filter' => 'login']);
// import container from file
$routes->add('/changecontainer/import_xls_pra', '\Modules\ChangeContainer\Controllers\ChangeContainer::import_xls_pra', ['filter' => 'login']);
$routes->add('/changecontainer/insertContainerFromFile/(:alphanum)', '\Modules\ChangeContainer\Controllers\ChangeContainer::insertContainerFromFile/$1', ['filter' => 'login']);


// ajax request
$routes->add('/changecontainer/ajax_ccode_listOne/(:alphanum)', '\Modules\ChangeContainer\Controllers\ChangeContainer::ajax_ccode_listOne/$1', ['filter' => 'login']);
$routes->add('/changecontainer/ajax_prcode_listOne/(:alphanum)', '\Modules\ChangeContainer\Controllers\ChangeContainer::ajax_prcode_listOne/$1', ['filter' => 'login']);
$routes->add('/changecontainer/ajax_vessel_listOne/(:any)', '\Modules\ChangeContainer\Controllers\ChangeContainer::ajax_vessel_listOne/$1', ['filter' => 'login']);
$routes->add('/changecontainer/ajax_voyage_list', '\Modules\ChangeContainer\Controllers\ChangeContainer::ajax_voyage_list', ['filter' => 'login']);
$routes->add('/changecontainer/ajax_view/(:alphanum)', '\Modules\ChangeContainer\Controllers\ChangeContainer::ajax_view/$1', ['filter' => 'login']);

// barcode_generator
$routes->get('/changecontainer/generate_barcode', '\Modules\ChangeContainer\Controllers\ChangeContainer::generate_barcode', ['filter' => 'login']);

