<?php

/**
 * Project name: Dorew MVC
 * Copyright (c) 2018 - 2024, Delopver: valedrat, agwinao
 */

use App\Providers\RouteServiceProvider;
use System\Providers\AppServiceProvider;
use System\Providers\CaptchaServiceProvider;

return [
    AppServiceProvider::class,
    RouteServiceProvider::class,
    CaptchaServiceProvider::class,
];
