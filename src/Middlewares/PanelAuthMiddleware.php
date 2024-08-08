<?php

namespace LVP\Middlewares;

use Closure;
use Illuminate\Http\Request;
use LVP\Facades\Panel;
use Symfony\Component\HttpFoundation\Response;

class PanelAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $panel_id): Response
    {
        /**
         * @var \LVP\Providers\PanelProvider $current_panel
         */
        $current_panel = app('lvp-current');
        if (!auth($panel_id)->check()) {
            return to_route($panel_id . '.login');
        }

        // $current_panel->bootResources();
        // $current_panel->bootPages();
        return $next($request);
    }
}
