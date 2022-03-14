<?php
$routes->add('damageprogress', '\Modules\Damageprogress\Controllers\Damageprogress::index', ['filter' => 'login']);
$routes->add('damageprogress/reportPdf/(:alphanum)', '\Modules\Damageprogress\Controllers\Damageprogress::reportPdf/$1', ['filter' => 'login']);
$routes->add('damageprogress/reportExcel/(:alphanum)', '\Modules\Damageprogress\Controllers\Damageprogress::reportExcel/$1', ['filter' => 'login']);
