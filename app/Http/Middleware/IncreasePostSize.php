<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IncreasePostSize
{
    /**
     * Handle an incoming request.
     * 
     * This middleware bypasses ValidatePostSize by setting a higher limit
     * and allowing the request to proceed even if it exceeds the default post_max_size
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Try to increase limits (may not work if already set, but worth trying)
        @ini_set('post_max_size', '150M');
        @ini_set('upload_max_filesize', '150M');
        @ini_set('max_execution_time', '300');
        @ini_set('max_input_time', '300');
        @ini_set('memory_limit', '512M');
        
        // Check if content length exceeds default but is within our limit
        $contentLength = $request->server('CONTENT_LENGTH');
        $maxAllowed = 150 * 1024 * 1024; // 150MB in bytes
        
        if ($contentLength && $contentLength > $maxAllowed) {
            abort(413, 'File size exceeds maximum allowed size of 150MB');
        }
        
        return $next($request);
    }
}

