<?php

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group([
    'domain' => parse_url(config('app.main_url'))['host'],
], function () {
    // These routes will only be served via the main domain...
    Route::get('/', fn () => 'Hello from main app URL');

    Route::get('/caddy-tls-domain-validator-y8soj3HcIrzAW4tQT7nk', function (Request $request) {
        $domain = $request->query('domain');

        if ($domain
            && (
                $domain === config('app.proxy_domain')
                || Team::query()->where('domain', $domain)->exists()
            )) {
            return response('OK');
        }

        abort(503);
    });

    Route::get('/test', fn () => [
        'message' => 'No team configured.',
    ]);
});

Route::group([
    'middleware' => [\App\Http\Middleware\TeamDomain::class],
], function () {
    // These routes will match any domain bound to the app...
    Route::view('/', 'welcome');

    Route::get('/test', function () {
        return [
            'message' => sprintf(
                'Team detected: %s',
                request()->attributes->get('team')->name,
            ),
        ];
    });

    Route::middleware([
        'auth:sanctum',
        config('jetstream.auth_session'),
        'verified',
    ])->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');

        Route::post('/queues', function () {
            \App\Jobs\TestJob::dispatch(request()->attributes->get('team'));

            return back()
                ->with('status', __('Job was dispatched.'));
        })->name('queues');
    });
});
