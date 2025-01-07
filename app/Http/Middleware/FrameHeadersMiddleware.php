<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class FrameHeadersMiddleware 
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        //$response->header('X-Frame-Options', '*');
        $response->headers->set('X-Frame-Options', 'ALLOWALL');//'ALLOW-FROM https://'.$_SERVER['HTTP_ORIGIN']);
        return $response;
    }
}