<?php

$routes->add('/gatein', '\Modules\GateIn\Controllers\GateIn::index', ['filter' => 'login']);
$routes->add('/gatein/add', '\Modules\GateIn\Controllers\GateIn::add', ['filter' => 'login']);
$routes->add('/gatein/list_data', '\Modules\GateIn\Controllers\GateIn::list_data', ['filter' => 'login']);
$routes->add('/gatein/get_data_gatein', '\Modules\GateIn\Controllers\GateIn::get_data_gatein', ['filter' => 'login']);

