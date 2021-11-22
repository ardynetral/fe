<?php

$routes->add('/gateout', '\Modules\GateOut\Controllers\GateOut::index', ['filter' => 'login']);
$routes->add('/gateout/add', '\Modules\GateOut\Controllers\GateOut::add', ['filter' => 'login']);
$routes->add('/gateout/list_data', '\Modules\GateOut\Controllers\GateOut::list_data', ['filter' => 'login']);
