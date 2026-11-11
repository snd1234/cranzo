<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function ($middleware) {
        $middleware->redirectGuestsTo(function ($request) {
            if ($request->is('system-auth') || $request->is('system-auth/*') || $request->is('admin/*')) {
                return route('login.form');
            }

            return route('customer.login');
        });
        $middleware->redirectUsersTo(fn () => route('account.dashboard'));
        $middleware->alias(['staff' => \App\Http\Middleware\EnsureStaff::class]);
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $exception, \Illuminate\Http\Request $request) {
            return redirect()->back(fallback: url('/'))
                ->withInput($request->except(['password', 'password_confirmation', 'current_password', '_token']))
                ->withErrors(['form' => 'The page expired. Refresh the page and submit the form again.']);
        });
    })->create();
