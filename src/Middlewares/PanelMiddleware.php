<?php

namespace LVP\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use LVP\Facades\Panel;
use Symfony\Component\HttpFoundation\Response;

class PanelMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Inertia::share([
            'alert' => fn() => $request->session()->get('alert'),
            'flash' => [
                'info' => fn() => $request->session()->get('info'),
                'status' => fn() => $request->session()->get('status'),
                'error' => fn() => $request->session()->get('error'),
                'success' => fn() => $request->session()->get('success'),
                'warning' => fn() => $request->session()->get('warning'),
                'alert' => fn() => $request->session()->get('alert'),
            ],
        ]);
        return $next($request);
    }
}
