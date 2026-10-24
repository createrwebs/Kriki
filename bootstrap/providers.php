<?php

use App\Providers\AiServiceProvider;
use App\Providers\AppServiceProvider;
use App\Providers\HostingServiceProvider;
use App\Providers\KrikkitUiServiceProvider;

return [
    AppServiceProvider::class,
    AiServiceProvider::class,
    KrikkitUiServiceProvider::class,
    HostingServiceProvider::class,
];
