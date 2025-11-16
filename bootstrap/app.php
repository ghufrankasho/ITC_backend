<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;
 

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
          $middleware->alias([
            'api' => APP\Http\Middleware\JwtMiddleware::class,
            'TrackVisitors'=>App\Http\Middleware\TrackVisitors::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        // JWT Token expired
        $exceptions->render(function (TokenExpiredException $e, $request) {
            return response()->json([
                'status' => false,
                'message' => 'Token has expired'
            ], 401);
        });

        // JWT Token invalid
        $exceptions->render(function (TokenInvalidException $e, $request) {
            return response()->json([
                'status' => false,
                'message' => 'Token is invalid'
            ], 401);
        });

        // JWT token not provided
        $exceptions->render(function (JWTException $e, $request) {
            return response()->json([
                'status' => false,
                'message' => 'Token not provided'
            ], 401);
        });

        // Authentication exception (user not logged in)
        $exceptions->render(function (AuthenticationException $e, $request) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated'
            ], 401);
        });

    })
    ->create();