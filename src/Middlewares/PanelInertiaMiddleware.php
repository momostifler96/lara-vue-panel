<?php

namespace LVP\Middlewares;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Middleware;
use LVP\Facades\LVPCurrentPanel;

class PanelInertiaMiddleware extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        /**
         * @var \LVP\Facades\Panel  $current_panel
         */
        // dd($request->session()->get('success'));
        $nav_menus = Cache::get('lvp-menus-' . 'admin', []);
        $user_menu = Cache::get('lvp-menus-user' . 'admin', []);
        $current_panel = app(LVPCurrentPanel::class)->panel;
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'notifications' => fn() => $request->user()->unreadNotifications->count(),
            'panel_data' => fn() => app(LVPCurrentPanel::class)->panel->getData(),
            'admin_logo' => $current_panel->getlogo(),
            'alert' => fn() => $request->session()->get('alert'),
            'flash' => [
                'info' => fn() => $request->session()->get('info'),
                'status' => fn() => $request->session()->get('status'),
                'error' => fn() => $request->session()->get('error'),
                'success' => fn() => $request->session()->get('success'),
                'warning' => fn() => $request->session()->get('warning'),
                'alert' => fn() => $request->session()->get('alert'),

            ],
            'currentPath' => $request->path(),
            'nav_menu' => $nav_menus,
            'user_menu' => $user_menu
        ];
    }
}
