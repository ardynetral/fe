<?php

$routes->add('/gateout', '\Modules\GateOut\Controllers\GateOut::index', ['filter' => 'login']);
$routes->add('/gateout/add', '\Modules\GateOut\Controllers\GateOut::add', ['filter' => 'login']);
$routes->add('/gateout/edit/(:alphanum)/(:alphanum)', '\Modules\GateOut\Controllers\GateOut::edit/$1/$2', ['filter' => 'login']);
$routes->add('/gateout/list_data', '\Modules\GateOut\Controllers\GateOut::list_data', ['filter' => 'login']);
$routes->add('/gateout/get_data_gateout', '\Modules\GateOut\Controllers\GateOut::get_data_gateout', ['filter' => 'login']);
$routes->add('/gateout/print_eir_out/(:alphanum)/(:alphanum)', '\Modules\GateOut\Controllers\GateOut::print_eir_out/$1/$2', ['filter' => 'login']);
