<?php

namespace LVP\Middlewares;

use Closure;
use Illuminate\Http\Request;
use LVP\Facades\Panel;
use Spatie\Multitenancy\Contracts\IsTenant;
use Symfony\Component\HttpFoundation\Response;

class PanelTenancyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $panel_id): Response
    {
        if (empty(app(IsTenant::class)::checkCurrent())) {
            abort(404);
        }
        return $next($request);
    }
}
