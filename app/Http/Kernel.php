<?php namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel {

    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        'Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode',
        'Illuminate\Cookie\Middleware\EncryptCookies',
        'Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse',
        'Illuminate\Session\Middleware\StartSession',
        'Illuminate\View\Middleware\ShareErrorsFromSession',
        'App\Http\Middleware\VerifyCsrfToken',
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => 'App\Http\Middleware\Authenticate',
        'auth.basic' => 'Illuminate\Auth\Middleware\AuthenticateWithBasicAuth',
        'guest' => 'App\Http\Middleware\RedirectIfAuthenticated',
        'referent' => 'App\Http\Middleware\RedirectIfNotReferent',
        'student' => 'App\Http\Middleware\RedirectIfNotStudent',
        'skrbnik' => 'App\Http\Middleware\RedirectIfNotSkrbnik',
        'ucitelj'=>  'App\Http\Middleware\RedirectIfNotUcitelj',
        'RefUci'=>  'App\Http\Middleware\RedirectIfNotRefUci',
        'StudRef'=> 'App\Http\Middleware\RedirectIfNotStudRef'
    ];

}
