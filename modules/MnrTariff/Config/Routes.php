<?php

$routes->add('/mnrtariff', '\Modules\MnrTariff\Controllers\MnrTariff::index', ['filter' => 'login']);
$routes->add('/mnrtariff/list_data', '\Modules\MnrTariff\Controllers\MnrTariff::list_data', ['filter' => 'login']);
$routes->add('/mnrtariff/view/(:alphanum)', '\Modules\MnrTariff\Controllers\MnrTariff::view/$1', ['filter' => 'login']);
$routes->add('/mnrtariff/add', '\Modules\MnrTariff\Controllers\MnrTariff::add', ['filter' => 'login']);
$routes->add('/mnrtariff/edit/(:alphanum)', '\Modules\MnrTariff\Controllers\MnrTariff::edit/$1', ['filter' => 'login']);
$routes->add('/mnrtariff/delete/(:alphanum)', '\Modules\MnrTariff\Controllers\MnrTariff::delete/$1', ['filter' => 'login']);
