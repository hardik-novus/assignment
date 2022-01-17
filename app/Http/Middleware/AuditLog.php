<?php

namespace App\Http\Middleware;

use App\Jobs\AuditLogJob;
use Closure;
use Illuminate\Http\Request;

class AuditLog
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Get logs inputs
        $requestEndpoint = $request->fullUrl();
        $requestMethod = $request->getMethod();
        $requestBody = json_encode($request->all());
        $requestIp = $request->ip();
        $requestUserAgent = $request->header('User-Agent');
        $responseBody = $response->getContent();
        $responseStatusCode = $response->getStatusCode();

        // Call job
        AuditLogJob::dispatch($requestEndpoint, $requestMethod, $requestBody, $requestIp, $requestUserAgent, $responseBody, $responseStatusCode);

        // Send response
        return $response;
    }
}
