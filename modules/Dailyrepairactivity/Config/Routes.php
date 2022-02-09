<?php

$routes->add('dailyrepairactivity', '\Modules\dailyrepairactivity\Controllers\dailyrepairactivity::index', ['filter' => 'login']);
$routes->add('dailyrepairactivity/view/(:alphanum)', '\Modules\dailyrepairactivity\Controllers\dailyrepairactivity::view/$1', ['filter' => 'login']);
$routes->add('dailyrepairactivity/reportPdf', '\Modules\dailyrepairactivity\Controllers\dailyrepairactivity::reportPdf', ['filter' => 'login']);
$routes->add('dailyrepairactivity/reportExcel', '\Modules\dailyrepairactivity\Controllers\dailyrepairactivity::reportExcel', ['filter' => 'login']);
