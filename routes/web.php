<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    /** @phpstan-var view-string $viewName */
    $viewName = 'welcome';
    return view($viewName);
});
