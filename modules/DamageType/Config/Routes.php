<?php

$routes->add('/damagetype', '\Modules\DamageType\Controllers\DamageType::index', ['filter' => 'login']);
$routes->add('/damagetype/view/(:alphanum)', '\Modules\DamageType\Controllers\DamageType::view/$1', ['filter' => 'login']);
$routes->add('/damagetype/add', '\Modules\DamageType\Controllers\DamageType::add', ['filter' => 'login']);
$routes->post('/damagetype/add', '\Modules\DamageType\Controllers\DamageType::add', ['filter' => 'login']);
$routes->add('/damagetype/edit/(:alphanum)', '\Modules\DamageType\Controllers\DamageType::edit/$1', ['filter' => 'login']);
$routes->post('/damagetype/edit/(:alphanum)', '\Modules\DamageType\Controllers\DamageType::edit/$1', ['filter' => 'login']);
$routes->add('/damagetype/delete/(:alphanum)', '\Modules\DamageType\Controllers\DamageType::delete/$1', ['filter' => 'login']);
$routes->add('/damagetype/list_data', '\Modules\DamageType\Controllers\DamageType::list_data', ['filter' => 'login']);