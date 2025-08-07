<?php

use App\Http\Middleware\CheckRoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
// use Illuminate\Auth\Middleware\Authenticate;
use App\Http\Middleware\RedirectIfAuthenticated;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: [
            'api/*', // Exclude all API routes from CSRF verification
            'api/v1/checkout-store', // Specific route for checkout store
            'api/v1/contact', // Specific route for contact form submission
            'api/v1/register', // Exclude registration route
            'api/v1/login', // Exclude login route
            'api/v1/reset-password/*', // Exclude reset password routes
            'api/v1/forgot-password', // Exclude forgot password route
            'api/v1/email-verification', // Exclude email verification route
            'api/v1/change-password', // Exclude change password route
            'api/v1/profile', // Exclude profile routes
            'api/v1/profile/edit', // Exclude profile edit route
            'api/v1/categories', // Exclude categories API route
        ]);
        $middleware->alias([
            // 'auth' => Authenticate::class,
            'guest' => RedirectIfAuthenticated::class,

            // 'check_role' => CheckRoleMiddleware::class,
            'localize'                => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRoutes::class,
            'localizationRedirect'    => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class,
            'localeSessionRedirect'   => \Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect::class,
            'localeCookieRedirect'    => \Mcamara\LaravelLocalization\Middleware\LocaleCookieRedirect::class,
            'localeViewPath'          => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationViewPath::class,

        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
