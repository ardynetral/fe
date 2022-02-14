<?php
$routes->add('damageprogress', '\Modules\Damageprogress\Controllers\Damageprogress::index', ['filter' => 'login']);
$routes->add('damageprogress/reportPdf', '\Modules\Damageprogress\Controllers\Damageprogress::reportPdf', ['filter' => 'login']);
$routes->add('damageprogress/reportExcel', '\Modules\Damageprogress\Controllers\Damageprogress::reportExcel', ['filter' => 'login']);
