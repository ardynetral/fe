<?php

$routes->add('/repotariff', '\Modules\RepoTariff\Controllers\RepoTariff::index', ['filter' => 'login']);
$routes->add('/repotariff/view/(:alphanum)', '\Modules\RepoTariff\Controllers\RepoTariff::view/$1', ['filter' => 'login']);
$routes->add('/repotariff/list_data', '\Modules\RepoTariff\Controllers\RepoTariff::list_data', ['filter' => 'login']);
$routes->add('/repotariff/add', '\Modules\RepoTariff\Controllers\RepoTariff::add', ['filter' => 'login']);
$routes->post('/repotariff/add', '\Modules\RepoTariff\Controllers\RepoTariff::add', ['filter' => 'login']);
$routes->add('/repotariff/edit/(:alphanum)/(:alphanum)', '\Modules\RepoTariff\Controllers\RepoTariff::edit/$1/$2', ['filter' => 'login']);
$routes->post('/repotariff/edit/(:alphanum)/(:alphanum)', '\Modules\RepoTariff\Controllers\RepoTariff::edit/$1/$2', ['filter' => 'login']);
$routes->add('/repotariff/delete/(:alphanum)', '\Modules\RepoTariff\Controllers\RepoTariff::delete/$1', ['filter' => 'login']);
// DETAIL TARIFF
$routes->add('/repotariff/add_detail', '\Modules\RepoTariff\Controllers\RepoTariff::add_detail', ['filter' => 'login']);
$routes->post('/repotariff/add_detail', '\Modules\RepoTariff\Controllers\RepoTariff::add_detail', ['filter' => 'login']);
$routes->add('/repotariff/edit_detail', '\Modules\RepoTariff\Controllers\RepoTariff::edit_detail', ['filter' => 'login']);
$routes->post('/repotariff/edit_detail', '\Modules\RepoTariff\Controllers\RepoTariff::edit_detail', ['filter' => 'login']);
// DELETE_DETAIL
$routes->post('/repotariff/delete_detail/(:alphanum)/(:alphanum)/(:alphanum)/(:alphanum)', '\Modules\RepoTariff\Controllers\RepoTariff::delete_detail/$1/$2/$3/$4', ['filter' => 'login']);
$routes->add('/repotariff/get_detail_repotype/(:alphanum)', '\Modules\RepoTariff\Controllers\RepoTariff::get_detail_repotype/$1', ['filter' => 'login']);
$routes->add('/repotariff/get_one_detail/(:alphanum)/(:alphanum)/(:alphanum)/(:alphanum)', '\Modules\RepoTariff\Controllers\RepoTariff::get_one_detail/$1/$2/$3/$4', ['filter' => 'login']);
$routes->add('/repotariff/load_detail_table', '\Modules\RepoTariff\Controllers\RepoTariff::load_detail_table', ['filter' => 'login']);
