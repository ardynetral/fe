<?php

$routes->add('losc', '\Modules\losc\Controllers\losc::index', ['filter' => 'login']);
$routes->add('losc/view/(:alphanum)', '\Modules\losc\Controllers\losc::view/$1', ['filter' => 'login']);
$routes->add('losc/reportPdf', '\Modules\losc\Controllers\losc::reportPdf', ['filter' => 'login']);
$routes->add('losc/reportExcel', '\Modules\losc\Controllers\losc::reportExcel', ['filter' => 'login']);
