<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BypassPostSizeValidation
{
    /**
     * Handle an incoming request.
     * 
     * This middleware runs BEFORE ValidatePostSize and modifies the request
     * to bypass the size validation for dashboard routes
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only bypass for dashboard routes
        if ($request->is('dashboard/*')) {
            // Temporarily modify CONTENT_LENGTH to bypass ValidatePostSize
            // We'll validate it ourselves in the controller
            $contentLength = $request->server('CONTENT_LENGTH');
            $maxAllowed = 150 * 1024 * 1024; // 150MB
            
            if ($contentLength && $contentLength > $maxAllowed) {
                abort(413, 'File size exceeds maximum allowed size of 150MB. Please compress images before uploading.');
            }
            
            // Try to increase PHP limits (may not work but worth trying)
            @ini_set('post_max_size', '150M');
            @ini_set('upload_max_filesize', '150M');
            @ini_set('max_execution_time', '300');
            @ini_set('max_input_time', '300');
            @ini_set('memory_limit', '512M');
        }
        
        return $next($request);
    }
}

