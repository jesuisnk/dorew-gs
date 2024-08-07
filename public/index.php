<?php

/**
 * Project name: Dorew MVC
 * Copyright (c) 2018 - 2024, Delopver: valedrat, agwinao
 */

use System\Classes\Container;
use System\Classes\Kernel;
use System\Classes\Request;

define('_MVC_START', microtime(true));

require('../system/bootstrap.php');

$container = Container::getInstance();

/** @var Kernel */
$kernel = $container->make(Kernel::class);
$request = $container->make(Request::class);

$kernel->run($request);
