<?php

$routes->add('statusreport', '\Modules\Statusreport\Controllers\Statusreport::index', ['filter' => 'login']);
$routes->add('statusreport/view/(:alphanum)', '\Modules\Statusreport\Controllers\Statusreport::view/$1', ['filter' => 'login']);
$routes->add('statusreport/reportPdf', '\Modules\Statusreport\Controllers\Statusreport::reportPdf', ['filter' => 'login']);
$routes->add('statusreport/reportExcel', '\Modules\Statusreport\Controllers\Statusreport::reportExcel', ['filter' => 'login']);
