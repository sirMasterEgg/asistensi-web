<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PassMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $request->headers->add(['ngrok-skip-browser-warning' => '1']);
        $request->headers->add(['Bypass-Tunnel-Reminder' => '1']);

        return $next($request);
    }
}
