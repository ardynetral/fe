<?php

$routes->add('containersearch', '\Modules\ContainerSearch\Controllers\ContainerSearch::index', ['filter' => 'login']);
$routes->add('containersearch/history', '\Modules\ContainerSearch\Controllers\ContainerSearch::history', ['filter' => 'login']);
$routes->add('containersearch/reportPdf', '\Modules\ContainerSearch\Controllers\ContainerSearch::reportPdf', ['filter' => 'login']);
$routes->add('containersearch/getContainer', '\Modules\ContainerSearch\Controllers\ContainerSearch::getContainer', ['filter' => 'login']);
