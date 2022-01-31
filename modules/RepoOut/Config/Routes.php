<?php

$routes->add('/repoout', '\Modules\RepoOut\Controllers\RepoOut::index', ['filter' => 'login']);
$routes->add('/repoout/view/(:alphanum)', '\Modules\RepoOut\Controllers\RepoOut::view/$1', ['filter' => 'login']);
$routes->add('/repoout/add', '\Modules\RepoOut\Controllers\RepoOut::add', ['filter' => 'login']);
$routes->post('/repoout/add', '\Modules\RepoOut\Controllers\RepoOut::add', ['filter' => 'login']);
$routes->post('/repoout/get_repo_tariff_detail', '\Modules\RepoOut\Controllers\RepoOut::get_repo_tariff_detail', ['filter' => 'login']);
$routes->add('/repoout/addcontainer', '\Modules\RepoOut\Controllers\RepoOut::addcontainer', ['filter' => 'login']);
$routes->add('/repoout/ajax_repo_containers', '\Modules\RepoOut\Controllers\RepoOut::ajax_repo_containers', ['filter' => 'login']);
$routes->add('/repoout/update_new_data', '\Modules\RepoOut\Controllers\RepoOut::update_new_data', ['filter' => 'login']);
$routes->add('/repoout/edit/(:alphanum)', '\Modules\RepoOut\Controllers\RepoOut::edit/$1', ['filter' => 'login']);
$routes->post('/repoout/edit/(:alphanum)', '\Modules\RepoOut\Controllers\RepoOut::edit/$1', ['filter' => 'login']);
$routes->add('/repoout/delete/(:alphanum)', '\Modules\RepoOut\Controllers\RepoOut::delete/$1', ['filter' => 'login']);
$routes->add('/repoout/getOneRepoContainer/(:alphanum)', '\Modules\RepoOut\Controllers\RepoOut::getOneRepoContainer/$1', ['filter' => 'login']);
$routes->post('/repoout/updatecontainer', '\Modules\RepoOut\Controllers\RepoOut::updatecontainer', ['filter' => 'login']);
$routes->add('/repoout/delete_container/(:alphanum)', '\Modules\RepoOut\Controllers\RepoOut::delete_container/$1', ['filter' => 'login']);
$routes->add('/repoout/checkContainerNumber', '\Modules\RepoOut\Controllers\RepoOut::checkContainerNumber', ['filter' => 'login']);
$routes->add('/repoout/ajax_ccode_listOne/(:alphanum)', '\Modules\RepoOut\Controllers\RepoOut::ajax_ccode_listOne/$1', ['filter' => 'login']);
$routes->add('/repoout/ajax_prcode_listOne/(:alphanum)', '\Modules\RepoOut\Controllers\RepoOut::ajax_prcode_listOne/$1', ['filter' => 'login']);
$routes->add('/repoout/ajax_vessel_listOne/(:any)', '\Modules\RepoOut\Controllers\RepoOut::ajax_vessel_listOne/$1', ['filter' => 'login']);
$routes->add('/repoout/ajax_voyage_list', '\Modules\RepoOut\Controllers\RepoOut::ajax_voyage_list', ['filter' => 'login']);
$routes->add('/repoout/cetak_kitir/(:alphanum)/(:alphanum)/(:alphanum)', '\Modules\RepoOut\Controllers\RepoOut::cetak_kitir/$1/$2/$3', ['filter' => 'login']);
$routes->add('/repoout/list_data', '\Modules\RepoOut\Controllers\RepoOut::list_data', ['filter' => 'login']);
$routes->add('/repoout/proforma/(:alphanum)', '\Modules\RepoOut\Controllers\RepoOut::proforma/$1', ['filter' => 'login']);


