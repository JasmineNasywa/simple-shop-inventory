<?php

use App\Providers\AppServiceProvider;
use App\Providers\Filament\AdminPanelProvider; // Tambahin ini di atas

return [
    AppServiceProvider::class,
    AdminPanelProvider::class,
];