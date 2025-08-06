<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    protected $except = [
        'api/*', // Exclude all API routes from CSRF verification
        'checkout-store', // Specific route for checkout store
        'contact', // Specific route for contact form submission
        'register', // Exclude registration route
        'login', // Exclude login route
        'reset-password/*', // Exclude reset password routes
        'forgot-password', // Exclude forgot password route
        'email-verification', // Exclude email verification route
        'change-password', // Exclude change password route
        'profile', // Exclude profile routes
        'profile/edit', // Exclude profile edit route
        'categories', // Exclude categories API route
    ];
}
