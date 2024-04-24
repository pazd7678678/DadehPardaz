<?php

use App\Providers\AppServiceProvider;
use App\Providers\Filament\AdminPanelProvider;
use Pzamani\Auth\AuthServiceProvider;
use Pzamani\Base\BaseServiceProvider;
use Pzamani\Payment\PaymentServiceProvider;

return [
    AppServiceProvider::class,
    AdminPanelProvider::class,
    AuthServiceProvider::class,
    BaseServiceProvider::class,
    PaymentServiceProvider::class,
];
