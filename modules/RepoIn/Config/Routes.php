<?php

$routes->add('/repoin', '\Modules\RepoIn\Controllers\RepoIn::index', ['filter' => 'login']);
$routes->add('/repoin/view/(:alphanum)', '\Modules\RepoIn\Controllers\RepoIn::view/$1', ['filter' => 'login']);
$routes->add('/repoin/add', '\Modules\RepoIn\Controllers\RepoIn::add', ['filter' => 'login']);
$routes->post('/repoin/add', '\Modules\RepoIn\Controllers\RepoIn::add', ['filter' => 'login']);
$routes->post('/repoin/get_repo_tariff_detail', '\Modules\RepoIn\Controllers\RepoIn::get_repo_tariff_detail', ['filter' => 'login']);
$routes->add('/repoin/addcontainer', '\Modules\RepoIn\Controllers\RepoIn::addcontainer', ['filter' => 'login']);
$routes->add('/repoin/ajax_repo_containers', '\Modules\RepoIn\Controllers\RepoIn::ajax_repo_containers', ['filter' => 'login']);
$routes->add('/repoin/update_new_data', '\Modules\RepoIn\Controllers\RepoIn::update_new_data', ['filter' => 'login']);
$routes->add('/repoin/edit/(:alphanum)', '\Modules\RepoIn\Controllers\RepoIn::edit/$1', ['filter' => 'login']);
$routes->post('/repoin/edit/(:alphanum)', '\Modules\RepoIn\Controllers\RepoIn::edit/$1', ['filter' => 'login']);
$routes->add('/repoin/delete/(:alphanum)', '\Modules\RepoIn\Controllers\RepoIn::delete/$1', ['filter' => 'login']);
$routes->add('/repoin/getOneRepoContainer/(:alphanum)', '\Modules\RepoIn\Controllers\RepoIn::getOneRepoContainer/$1', ['filter' => 'login']);
$routes->post('/repoin/updatecontainer', '\Modules\RepoIn\Controllers\RepoIn::updatecontainer', ['filter' => 'login']);
$routes->add('/repoin/delete_container/(:alphanum)', '\Modules\RepoIn\Controllers\RepoIn::delete_container/$1', ['filter' => 'login']);
$routes->add('/repoin/checkContainerNumber', '\Modules\RepoIn\Controllers\RepoIn::checkContainerNumber', ['filter' => 'login']);
$routes->add('/repoin/ajax_ccode_listOne/(:alphanum)', '\Modules\RepoIn\Controllers\RepoIn::ajax_ccode_listOne/$1', ['filter' => 'login']);
$routes->add('/repoin/ajax_prcode_listOne/(:alphanum)', '\Modules\RepoIn\Controllers\RepoIn::ajax_prcode_listOne/$1', ['filter' => 'login']);
$routes->add('/repoin/ajax_vessel_listOne/(:any)', '\Modules\RepoIn\Controllers\RepoIn::ajax_vessel_listOne/$1', ['filter' => 'login']);
$routes->add('/repoin/ajax_voyage_list', '\Modules\RepoIn\Controllers\RepoIn::ajax_voyage_list', ['filter' => 'login']);
$routes->add('/repoin/cetak_kitir/(:alphanum)/(:alphanum)/(:alphanum)', '\Modules\RepoIn\Controllers\RepoIn::cetak_kitir/$1/$2/$3', ['filter' => 'login']);
$routes->add('/repoin/list_data', '\Modules\RepoIn\Controllers\RepoIn::list_data', ['filter' => 'login']);
$routes->add('/repoin/proforma/(:alphanum)', '\Modules\RepoIn\Controllers\RepoIn::proforma/$1', ['filter' => 'login']);


