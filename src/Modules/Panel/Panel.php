<?php

namespace LVP\Modules\Panel;

use LVP\Defaults\DashboardPage;
use LVP\Defaults\LoginPage;
use LVP\Middlewares\PanelGuestInertiaMiddleware;
use LVP\Middlewares\PanelInertiaMiddleware;
use LVP\Middlewares\PanelMiddleware;
use LVP\Modules\Panel\Features\Actions;
use LVP\Modules\Panel\Features\Fluents;
use LVP\Modules\Panel\Features\Geters;
use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Hashing\Hasher;
//-middleware
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class Panel
{
    use Actions, Fluents, Geters;
    #Panel settings
    protected $_label = '';
    protected $_id = '';
    protected $_local = 'en';
    protected $_settings = [
        'has_notifications' => true,
        'has_messages' => true,
        'menu_position' => 'top',
        'has_spotlight' => true,
    ];
    protected $_gender = 'male';
    protected $_logo = 'https://momoledev.com/assets/images/momoledev-logo-dark-black.svg';
    protected array $_middlewares = [
        EncryptCookies::class,
        AddQueuedCookiesToResponse::class,
        StartSession::class,
        AuthenticateSession::class,
        ShareErrorsFromSession::class,
        VerifyCsrfToken::class,
        SubstituteBindings::class,
        PanelMiddleware::class,
    ];

    protected array $_guest_middlewares = [
        PanelGuestInertiaMiddleware::class
    ];
    protected array $_panel_middlewares = [
        PanelInertiaMiddleware::class
    ];

    #login page
    /**
     * @var DashboardPage 
     */
    protected $_dashboard_class = DashboardPage::class;
    protected $_dashboard;
    protected $_auth_provider = 'App\Models\User';
    protected $_dashboard_view_path = 'LVP/Dashboard';

    #login page
    /**
     * 
     * @var LoginPage
     */
    protected $_login_page;
    protected $_login_page_class;


    #regsiter page
    protected $_register_page;
    #--------------------------------

    /**
     * Summary of _pages
     * @var \LVP\Support\Resource[]
     */
    protected $_resources = [];
    protected $_resources_path;
    #--------------------------------

    /**
     * Summary of _pages
     * @var \LVP\Support\Page[]
     */
    protected $_pages = [];
    protected $_pages_path;
    #--------------------------------
    #Routing infos
    protected $_route_name = '';
    protected $_route_path = '';
    protected $_routes = [];
    protected $_custom_nav_links = [];
    protected $_nav_menu = [];
    protected $_user_menu = [];
    protected $_menu_groups = [];


}