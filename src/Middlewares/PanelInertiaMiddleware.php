<?php

namespace LVP\Middlewares;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
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

        $current_panel = app('lvp-current');


        $shared_data = [
            ...parent::share($request),
            'auth' => [
                'user' => null,
                'role' => null,
                'permissions' => [],
            ],
            'notifications' => 0,
            'panel_data' => fn() => $current_panel->getData(),
            'admin_logo' => $current_panel->getlogo(),
            'alert' => fn() => $request->session()->get('alert'),
            'nav_menu' => $current_panel->getNavMenu(),
            'user_menu' => $current_panel->getUserMenu()
        ];

        if (auth($current_panel->getId())->check()) {
            $shared_data['auth']['user'] = auth($current_panel->getId())->user();
            $shared_data['auth']['role'] = auth($current_panel->getId())->user()->role;
            $shared_data['auth']['permissions'] = auth($current_panel->getId())->user()->permissions;
            $shared_data['notifications'] = auth($current_panel->getId())->user()->unreadNotifications->count();
        }

        return $shared_data;
    }

}
