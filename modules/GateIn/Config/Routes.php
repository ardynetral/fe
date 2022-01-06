<?php

$routes->add('/gatein', '\Modules\GateIn\Controllers\GateIn::index', ['filter' => 'login']);
$routes->add('/gatein/add', '\Modules\GateIn\Controllers\GateIn::add', ['filter' => 'login']);
$routes->add('/gatein/edit/(:alphanum)', '\Modules\GateIn\Controllers\GateIn::edit/$1', ['filter' => 'login']);
$routes->add('/gatein/list_data', '\Modules\GateIn\Controllers\GateIn::list_data', ['filter' => 'login']);
$routes->add('/gatein/get_data_gatein', '\Modules\GateIn\Controllers\GateIn::get_data_gatein', ['filter' => 'login']);
$routes->add('/gatein/print_eir/(:alphanum)', '\Modules\GateIn\Controllers\GateIn::print_eir/$1', ['filter' => 'login']);

