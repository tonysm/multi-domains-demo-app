<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'domain' => parse_url(config('app.main_url'))['host'],
], function () {
    Route::get('/', fn () => 'Hello from main app URL');
});

Route::group([
    'middleware' => [\App\Http\Middleware\TeamDomain::class],
], function () {
    Route::get('/', function () {
        return view('welcome');
    });

    Route::middleware([
        'auth:sanctum',
        config('jetstream.auth_session'),
        'verified',
    ])->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');
    });
});
