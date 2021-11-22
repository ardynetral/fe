<?php
$routes->get('/dashboard', '\Modules\Dashboard\Controllers\Dashboard::index', ['filter' => 'login']);