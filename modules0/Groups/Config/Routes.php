<?php

$routes->get('/groups', '\Modules\Groups\Controllers\Groups::index', ['filter' => 'login']);