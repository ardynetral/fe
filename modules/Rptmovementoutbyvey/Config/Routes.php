<?php

$routes->add('rptmovementoutbyvey', '\Modules\Rptmovementoutbyvey\Controllers\Rptmovementoutbyvey::index', ['filter' => 'login']);
$routes->add('rptmovementoutbyvey/reportPdf/(:any)/(:any)/(:any)/(:any)', '\Modules\Rptmovementoutbyvey\Controllers\Rptmovementoutbyvey::reportPdf/$1/$2/$3/$4', ['filter' => 'login']);
$routes->add('rptmovementoutbyvey/reportExcel/(:any)/(:any)/(:any)/(:any)', '\Modules\Rptmovementoutbyvey\Controllers\Rptmovementoutbyvey::reportExcel/$1/$2/$3/$4', ['filter' => 'login']);
