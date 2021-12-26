<?php

$routes->add('/repoin', '\Modules\RepoIn\Controllers\RepoIn::index', ['filter' => 'login']);
$routes->add('/repoin/view/(:alphanum)', '\Modules\RepoIn\Controllers\RepoIn::view/$1', ['filter' => 'login']);
$routes->add('/repoin/add', '\Modules\RepoIn\Controllers\RepoIn::add', ['filter' => 'login']);
$routes->post('/repoin/add', '\Modules\RepoIn\Controllers\RepoIn::add', ['filter' => 'login']);
$routes->add('/repoin/addcontainer', '\Modules\RepoIn\Controllers\RepoIn::addcontainer', ['filter' => 'login']);
$routes->add('/repoin/edit/(:alphanum)', '\Modules\RepoIn\Controllers\RepoIn::edit/$1', ['filter' => 'login']);
$routes->post('/repoin/edit/(:alphanum)', '\Modules\RepoIn\Controllers\RepoIn::edit/$1', ['filter' => 'login']);
$routes->add('/repoin/delete/(:alphanum)', '\Modules\RepoIn\Controllers\RepoIn::delete/$1', ['filter' => 'login']);
$routes->add('/repoin/checkContainerNumber', '\Modules\RepoIn\Controllers\RepoIn::checkContainerNumber', ['filter' => 'login']);
$routes->add('/repoin/ajax_ccode_listOne/(:alphanum)', '\Modules\RepoIn\Controllers\RepoIn::ajax_ccode_listOne/$1', ['filter' => 'login']);
$routes->add('/repoin/ajax_prcode_listOne/(:alphanum)', '\Modules\RepoIn\Controllers\RepoIn::ajax_prcode_listOne/$1', ['filter' => 'login']);
$routes->add('/repoin/ajax_vessel_listOne/(:any)', '\Modules\RepoIn\Controllers\RepoIn::ajax_vessel_listOne/$1', ['filter' => 'login']);
$routes->add('/repoin/ajax_voyage_list', '\Modules\RepoIn\Controllers\RepoIn::ajax_voyage_list', ['filter' => 'login']);
$routes->add('/repoin/print_order/(:alphanum)', '\Modules\RepoIn\Controllers\RepoIn::print_order/$1', ['filter' => 'login']);
$routes->add('/repoin/list_data', '\Modules\RepoIn\Controllers\RepoIn::list_data', ['filter' => 'login']);

