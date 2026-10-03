<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyIotApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $configuredKey = (string) config('services.iot.api_key');
        $requestKey = (string) $request->header('X-API-KEY');

        if ($configuredKey === '' || $requestKey === '' || ! hash_equals($configuredKey, $requestKey)) {
            return response()->json(['message' => 'Unauthorized.'], 401);
        }

        return $next($request);
    }
}
