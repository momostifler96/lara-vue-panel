<?php

namespace LVP\Providers;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use LVP\Defaults\DashboardPage;
use LVP\Defaults\LoginPage;
use LVP\Extensions\AuthGuard;
use LVP\Extensions\AuthProvider;
use LVP\Facades\LVPCurrentPanel;
use LVP\Middlewares\PanelAuthMiddleware;
use LVP\Middlewares\PanelGuestInertiaMiddleware;
use LVP\Middlewares\PanelGuestMiddleware;
use LVP\Middlewares\PanelInertiaMiddleware;
use LVP\Modules\Panel\Panel;
use LVP\Support\PanelNavLink;

class PanelProvider extends \Illuminate\Support\ServiceProvider
{
    private Panel $panel;

    protected function settings(Panel $panel)
    {

    }

    public function register()
    {
        $this->panel = new Panel();
        $this->settings($this->panel);
        $this->panel->registerBasePages();
        $this->panel->registerResources();
        $this->panel->registerPages();
        $this->panel->registerRoutes();


        if (str_starts_with(request()->path(), $this->panel->getPanelRoutePath())) {
            app()->singleton('lvp-current', function () {
                return $this->panel;
            });
        }
    }

    public function boot()
    {
        $this->panel->configureGuards();
        $this->panel->buildMenus();

        $this->panel->bootResources();
        $this->panel->bootPages();
    }

}
