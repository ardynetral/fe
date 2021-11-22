<?php

$routes->add('/repotariff', '\Modules\RepoTariff\Controllers\RepoTariff::index', ['filter' => 'login']);
$routes->add('/repotariff/view/(:alphanum)', '\Modules\RepoTariff\Controllers\RepoTariff::view/$1', ['filter' => 'login']);
$routes->add('/repotariff/add', '\Modules\RepoTariff\Controllers\RepoTariff::add', ['filter' => 'login']);
$routes->post('/repotariff/add', '\Modules\RepoTariff\Controllers\RepoTariff::add', ['filter' => 'login']);
$routes->add('/repotariff/edit/(:alphanum)', '\Modules\RepoTariff\Controllers\RepoTariff::edit/$1', ['filter' => 'login']);
$routes->post('/repotariff/edit/(:alphanum)', '\Modules\RepoTariff\Controllers\RepoTariff::edit/$1', ['filter' => 'login']);
$routes->add('/repotariff/delete/(:alphanum)', '\Modules\RepoTariff\Controllers\RepoTariff::delete/$1', ['filter' => 'login']);
// DETAIL TARIFF
$routes->add('/repotariff/add_detail', '\Modules\RepoTariff\Controllers\RepoTariff::add_detail', ['filter' => 'login']);
$routes->post('/repotariff/add_detail', '\Modules\RepoTariff\Controllers\RepoTariff::add_detail', ['filter' => 'login']);
$routes->add('/repotariff/edit_detail', '\Modules\RepoTariff\Controllers\RepoTariff::add_detail', ['filter' => 'login']);
$routes->post('/repotariff/edit_detail', '\Modules\RepoTariff\Controllers\RepoTariff::add_detail', ['filter' => 'login']);
$routes->post('/repotariff/delete_detail', '\Modules\RepoTariff\Controllers\RepoTariff::delete_detail', ['filter' => 'login']);