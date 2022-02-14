<?php

$routes->add('dailymovementout', '\Modules\Dailymovementout\Controllers\Dailymovementout::index', ['filter' => 'login']);
$routes->add('dailymovementout/reportPdf', '\Modules\Dailymovementout\Controllers\Dailymovementout::reportPdf', ['filter' => 'login']);
$routes->add('dailymovementout/reportExcel', '\Modules\Dailymovementout\Controllers\Dailymovementout::reportExcel', ['filter' => 'login']);
