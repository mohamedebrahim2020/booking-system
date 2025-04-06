<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\IsAdminMiddleware;
use Illuminate\Auth\AuthenticationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
			'is_admin' => IsAdminMiddleware::class,
		]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
		$exceptions->render(function (AuthenticationException $e, $request) {
			return response()->json([
				'message' => 'Unauthenticated.'
			], 401);
		})->render(function (Exception $e, $request) {
			if ($e->getMessage() === "Your email address is not verified.") {
				return response()->json([
					'message' => 'Please verify your email before accessing this resource.'
				], 403);
			}

			return null;
			});

    })->create();
