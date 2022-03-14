<?php

$routes->add('dailyrepairactivity', '\Modules\Dailyrepairactivity\Controllers\Dailyrepairactivity::index', ['filter' => 'login']);
$routes->add('dailyrepairactivity/reportPdf/(:any)/(:any)', '\Modules\Dailyrepairactivity\Controllers\Dailyrepairactivity::reportPdf/$1/$2', ['filter' => 'login']);
$routes->add('dailyrepairactivity/reportExcel/(:any)/(:any)', '\Modules\Dailyrepairactivity\Controllers\Dailyrepairactivity::reportExcel/$1/$2', ['filter' => 'login']);
