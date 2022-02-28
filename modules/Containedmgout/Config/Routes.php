<?php

$routes->add('/containedmgout', '\Modules\Containedmgout\Controllers\Containedmgout::index', ['filter' => 'login']);
$routes->add('/containedmgout/add', '\Modules\Containedmgout\Controllers\Containedmgout::add', ['filter' => 'login']);
$routes->post('/containedmgout/add', '\Modules\Containedmgout\Controllers\Containedmgout::add', ['filter' => 'login']);
$routes->add('/containedmgout/delete/(:alphanum)', '\Modules\Containedmgout\Controllers\Containedmgout::delete/$1', ['filter' => 'login']);
$routes->add('/containedmgout/list_data', '\Modules\Containedmgout\Controllers\Containedmgout::list_data', ['filter' => 'login']);