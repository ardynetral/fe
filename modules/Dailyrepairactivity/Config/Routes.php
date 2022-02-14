<?php

$routes->add('dailyrepairactivity', '\Modules\Dailyrepairactivity\Controllers\Dailyrepairactivity::index', ['filter' => 'login']);
$routes->add('dailyrepairactivity/reportPdf', '\Modules\Dailyrepairactivity\Controllers\Dailyrepairactivity::reportPdf', ['filter' => 'login']);
$routes->add('dailyrepairactivity/reportExcel', '\Modules\Dailyrepairactivity\Controllers\Dailyrepairactivity::reportExcel', ['filter' => 'login']);
