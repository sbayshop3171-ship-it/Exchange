<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class HandleWebhookExceptions
{
    public function handle(Request $request, Closure $next)
    {
        try {
            return $next($request);
        } catch (ValidationException $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->errors(),
            ], 422);
        } catch (Throwable $exception) {
            report($exception);

            $status = $exception instanceof HttpExceptionInterface && $exception->getStatusCode() >= 400 && $exception->getStatusCode() < 500
                ? $exception->getStatusCode()
                : 400;

            return response()->json([
                'status' => 'error',
                'message' => ['Invalid or incomplete webhook payload.'],
            ], $status);
        }
    }
}