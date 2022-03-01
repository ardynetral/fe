<?php

$routes->add('dailymovementout', '\Modules\Dailymovementout\Controllers\Dailymovementout::index', ['filter' => 'login']);
$routes->add('dailymovementout/reportPdf/(:any)/(:any)/(:any)/(:any)/(:any)', '\Modules\Dailymovementout\Controllers\Dailymovementout::reportPdf/$1/$2/$3/$4/$5', ['filter' => 'login']);
$routes->add('dailymovementout/reportExcel/(:any)/(:any)/(:any)/(:any)/(:any)', '\Modules\Dailymovementout\Controllers\Dailymovementout::reportExcel/$1/$2/$3/$4/$5', ['filter' => 'login']);
