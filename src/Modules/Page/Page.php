<?php

namespace LVP\Modules\Page;

use Illuminate\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use LVP\Defaults\LoginPage;
use LVP\Defaults\RegisterPage;
use LVP\Middlewares\PanelInertiaMiddleware;
use LVP\Modules\Page\Features\Actions;
use LVP\Modules\Page\Features\Booting;
use LVP\Modules\Page\Features\Controllers;
use LVP\Modules\Page\Features\Geters;
use LVP\Modules\Page\Features\Hooks;
use LVP\Providers\PanelProvider;

class Page
{
    use Actions, Booting, Geters, Hooks, Controllers;
    #Page infos
    public bool $disabled = false;
    protected bool $has_full_path = false;
    protected bool $has_rest_route = false;
    protected bool $is_sub_page = false;
    protected string $parent_route_name;
    protected string $parent_route_path;

    #Labels
    protected string $page_title;
    protected string $meta_title = '';
    protected string $meta_description = '';

    #Routing infos
    protected string $slug;
    protected string $route_name;
    protected string $_panel_route_path;
    protected string $_panel_route_name;


    #Labels
    protected string $label;
    protected string $short_label;
    protected string $nav_menu_label;
    protected string $plural_label;

    protected array $http_methods = ['get', 'post'];
    protected string $view_path = 'LVP/PageTemplate';
    protected string $menu_label;
    protected string $menu_group;
    public int $menu_position = 0;
    public string $menu_icon = 'stack';
    protected bool $show_in_menu = true;


    protected array $_middlewares = [];
    protected array $index_middlewares = [];
    protected array $post_middlewares = [];
    protected array $put_middlewares = [];
    protected array $delete_middlewares = [];
    protected string $local = 'en';


    protected \Illuminate\Foundation\Auth\User|null $current_user;



}
