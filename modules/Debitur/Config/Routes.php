<?php

$routes->add('/debitur', '\Modules\Debitur\Controllers\Debitur::index', ['filter' => 'login']);
$routes->add('/debitur/view/(:alphanum)', '\Modules\Debitur\Controllers\Debitur::view/$1', ['filter' => 'login']);
$routes->add('/debitur/add', '\Modules\Debitur\Controllers\Debitur::add', ['filter' => 'login']);
$routes->post('/debitur/add', '\Modules\Debitur\Controllers\Debitur::add', ['filter' => 'login']);
$routes->add('/debitur/edit/(:alphanum)', '\Modules\Debitur\Controllers\Debitur::edit/$1', ['filter' => 'login']);
$routes->post('/debitur/edit/(:alphanum)', '\Modules\Debitur\Controllers\Debitur::edit/$1', ['filter' => 'login']);
$routes->add('/debitur/delete/(:alphanum)', '\Modules\Debitur\Controllers\Debitur::delete/$1', ['filter' => 'login']);
$routes->add('/debitur/ajax_country', '\Modules\Debitur\Controllers\Debitur::ajax_country', ['filter' => 'login']);
