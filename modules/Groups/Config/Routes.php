<?php

$routes->get('/groups', '\Modules\Groups\Controllers\Groups::index', ['filter' => 'login']);
$routes->add('/groups/set_privilege/(:alphanum)', '\Modules\Groups\Controllers\Groups::set_privilege/$1', ['filter' => 'login']);
$routes->add('/groups/insert_group_privilege/(:alphanum)', '\Modules\Groups\Controllers\Groups::insert_group_privilege/$1', ['filter' => 'login']);