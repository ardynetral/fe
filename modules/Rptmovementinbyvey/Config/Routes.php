<?php

$routes->add('rptmovementinbyvey', '\Modules\Rptmovementinbyvey\Controllers\Rptmovementinbyvey::index', ['filter' => 'login']);
$routes->add('rptmovementinbyvey/reportPdf/(:any)/(:any)/(:any)/(:any)', '\Modules\Rptmovementinbyvey\Controllers\Rptmovementinbyvey::reportPdf/$1/$2/$3/$4', ['filter' => 'login']);
$routes->add('rptmovementinbyvey/reportExcel/(:any)/(:any)/(:any)/(:any)', '\Modules\Rptmovementinbyvey\Controllers\Rptmovementinbyvey::reportExcel/$1/$2/$3/$4', ['filter' => 'login']);
