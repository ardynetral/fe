<?php

$routes->add('/cfsprincipal', '\Modules\CfsPrincipal\Controllers\CfsPrincipal::index', ['filter' => 'login']);
$routes->add('/cfsprincipal/list_data', '\Modules\CfsPrincipal\Controllers\CfsPrincipal::list_data', ['filter' => 'login']);
// $routes->add('/prain/view/(:alphanum)', '\Modules\PraIn\Controllers\PraIn::view/$1', ['filter' => 'login']);
// $routes->add('/prain/add', '\Modules\PraIn\Controllers\PraIn::add', ['filter' => 'login']);
// $routes->add('/prain/edit/(:alphanum)', '\Modules\PraIn\Controllers\PraIn::edit/$1', ['filter' => 'login']);
// $routes->add('/prain/delete/(:alphanum)', '\Modules\PraIn\Controllers\PraIn::delete/$1', ['filter' => 'login']);
