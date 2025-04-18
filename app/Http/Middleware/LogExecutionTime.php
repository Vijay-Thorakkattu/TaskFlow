<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LogExecutionTime
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $start = microtime(true);
        $response = $next($request);
        $ms = round((microtime(true) - $start) * 1000, 2);
        Log::info('API Request', [
            'method'             => $request->method(),
            'uri'                => $request->getRequestUri(),
            'status'             => $response->getStatusCode(),
            'execution_time_ms'  => $ms . ' ms',
        ]);

        return $response;
    }
}
