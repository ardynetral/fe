<?php

$routes->add('/dailyrepairactivity', '\Modules\Dailyrepairactivity\Controllers\Dailyrepairactivity::index', ['filter' => 'login']);
$routes->add('/dailyrepairactivity/view/(:alphanum)', '\Modules\Dailyrepairactivity\Controllers\Dailyrepairactivity::view/$1', ['filter' => 'login']);
$routes->add('/dailyrepairactivity/add', '\Modules\Dailyrepairactivity\Controllers\Dailyrepairactivity::add', ['filter' => 'login']);
$routes->post('/dailyrepairactivity/add', '\Modules\Dailyrepairactivity\Controllers\Dailyrepairactivity::add', ['filter' => 'login']);
$routes->add('/dailyrepairactivity/edit/(:alphanum)', '\Modules\Dailyrepairactivity\Controllers\Dailyrepairactivity::edit/$1', ['filter' => 'login']);
$routes->post('/dailyrepairactivity/edit/(:alphanum)', '\Modules\Dailyrepairactivity\Controllers\Dailyrepairactivity::edit/$1', ['filter' => 'login']);
$routes->add('/dailyrepairactivity/delete/(:alphanum)', '\Modules\Dailyrepairactivity\Controllers\Dailyrepairactivity::delete/$1', ['filter' => 'login']);
$routes->add('/dailyrepairactivity/ajax_country', '\Modules\Dailyrepairactivity\Controllers\Dailyrepairactivity::ajax_country', ['filter' => 'login']);
// $routes->add('/dailymovementout/ajax_customer', '\Modules\Principal\Controllers\Principal::ajax_country', ['filter' => 'login']);
