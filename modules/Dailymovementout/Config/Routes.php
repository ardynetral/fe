<?php

$routes->add('dailymovementout', '\Modules\dailymovementout\Controllers\dailymovementout::index', ['filter' => 'login']);
$routes->add('dailymovementout/view/(:alphanum)', '\Modules\dailymovementout\Controllers\dailymovementout::view/$1', ['filter' => 'login']);
$routes->add('dailymovementout/reportPdf', '\Modules\dailymovementout\Controllers\dailymovementout::reportPdf', ['filter' => 'login']);
$routes->add('dailymovementout/reportExcel', '\Modules\dailymovementout\Controllers\dailymovementout::reportExcel', ['filter' => 'login']);
