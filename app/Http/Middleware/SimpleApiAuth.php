<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SimpleApiAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Get API Key from header or query string
        $key = $request->header('X-API-KEY') ?? $request->query('api_key');
        
        // Expected key is MD5 of "MALIKTAUFIKKURNIAWAN"
        $expectedKey = md5('MALIKTAUFIKKURNIAWAN');

        if ($key !== $expectedKey) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Invalid API Key.',
            ], 401);
        }

        return $next($request);
    }
}
