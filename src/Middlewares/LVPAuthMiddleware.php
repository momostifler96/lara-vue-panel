<?php

namespace LVP\Middlewares;

use Closure;
use Illuminate\Http\Request;
use LVP\Facades\Panel;
use Symfony\Component\HttpFoundation\Response;

class LVPAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $panel_id): Response
    {

        if (!auth()->check()) {
            return to_route($panel_id . '.login');
        }
        return $next($request);
    }
}
