<?php

namespace LVP\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use LVP\Facades\Panel;
use Symfony\Component\HttpFoundation\Response;

class PanelGuestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $panel_id): Response
    {
        if (auth($panel_id)->check()) {
            // /**
            //  * @var \LVP\Providers\PanelProvider $current_panel
            //  */
            $current_panel = app('lvp-current');
            return to_route($panel_id);
        }
        return $next($request);
    }
}
