<?php

namespace LVP\Modules\Panel\Features;

use Illuminate\Auth\SessionGuard;
use Illuminate\Support\Facades\File;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use LVP\Middlewares\PanelGuestInertiaMiddleware;
use LVP\Middlewares\PanelInertiaMiddleware;
use LVP\Support\PanelNavLink;

trait Actions
{


    public function registerBasePages()
    {
        $this->_dashboard = new $this->_dashboard_class();
        if (!empty($this->_login_page_class)) {
            $this->_login_page = new $this->_login_page_class();
        }
    }
    public function registerRoutes()
    {
        Route::middleware($this->_middlewares)->group(function () {
            if (!empty($this->_login_page_class)) {
                Route::middleware($this->_guest_middlewares)->prefix($this->getPanelRouteName())->as($this->getPanelRouteName())->group(function () {
                    Route::get('/login', fn(Request $request) => $this->_login_page->index($request))->name('.login');
                    Route::post('/login', fn(Request $request) => $this->_login_page->login($request))->name('.login.store');
                    Route::get('/register', fn(Request $request) => $this->_login_page->login($request))->name('.register');
                    Route::post('/register', fn(Request $request) => $this->_login_page->index($request))->name('.register.store');
                });
            }
            Route::middleware($this->_panel_middlewares)->group(function () {
                Route::prefix($this->getPanelRouteName())->as($this->getPanelRouteName())->group(function () {
                    Route::get('/', fn(Request $request) => $this->_dashboard->index($request));
                    Route::post('/', fn(Request $request) => $this->_dashboard->post($request))->name('.post');
                });

                foreach ($this->_resources as $key => $resource) {
                    $resource->registerRoutes();
                }
                foreach ($this->_pages as $key => $page) {
                    $page->registerRoutes();
                }
            });
        });
    }
    public function registerResources()
    {

        $resources_path = $this->_resources_path ?? app_path('LVP/Resources');
        $resource_files = File::glob($resources_path . '/*.php');

        foreach ($resource_files as $file) {
            $file_contents = File::get($file);

            if (
                preg_match('/namespace (.+);/', $file_contents, $namespace_matches) &&
                preg_match('/class (\w+)/', $file_contents, $class_matches)
            ) {
                $namespace = $namespace_matches[1];
                $class = $class_matches[1];
                $resource_class = $namespace . '\\' . $class;
                /**
                 * @var \LVP\Modules\Resource\Resource $resource_instance
                 */
                $resource_instance = new $resource_class($this);
                if (!$resource_instance->disabled) {
                    $resource_instance->register($this);
                    $this->_resources[] = $resource_instance;
                }
            }
        }
    }
    public function registerPages()
    {

        $pages_path = $this->_pages_path ?? app_path('LVP/Pages');

        $page_files = File::glob($pages_path . '/*.php');

        foreach ($page_files as $file) {
            $file_contents = File::get($file);

            if (
                preg_match('/namespace (.+);/', $file_contents, $namespace_matches) &&
                preg_match('/class (\w+)/', $file_contents, $class_matches)
            ) {
                $namespace = $namespace_matches[1];
                $class = $class_matches[1];
                $page_class = $namespace . '\\' . $class;
                /**
                 * @var \LVP\Modules\Page\Page $page_instance
                 */
                $page_instance = new $page_class($this);
                if (!$page_instance->disabled) {
                    $page_instance->register($this);
                    $this->_pages[] = $page_instance;
                }
            }
        }
    }
    #------------
    #Geters
    public function bootResources()
    {
        foreach ($this->_resources as $key => $resource) {
            $resource->boot($this);
        }
    }
    public function bootPages()
    {
        foreach ($this->_pages as $key => $page) {
            $page->boot($this);
        }
    }

    public function user()
    {
        return auth($this->_id)->user();
    }


    public function buildMenus()
    {
        $nav_menu_cache_id = 'lvp-nav-menu-panel-' . $this->_id;
        $nav_menu = config('laravue-panel.env') == 'local' ? [] : cache($nav_menu_cache_id);
        if (empty($nav_menu)) {
            $saved_menus = [];
            $simple_menus = [];
            foreach ($this->_resources as $key => $resource) {
                if ($resource->canShowMenu()) {
                    $menu_group = $resource->getMenuGroup();
                    $resource_menu = $resource->getNavMenu();
                    if (!empty($menu_group)) {
                        $resource_menu['group'] = $menu_group;
                    }
                    $simple_menus[] = $resource_menu;
                }

            }
            foreach ($this->_pages as $key => $page) {
                if ($page->canShowMenu()) {
                    $menu_group = $page->getMenuGroup();
                    $page_menu = $page->getNavMenu();
                    if (!empty($menu_group)) {
                        $page_menu['group'] = $menu_group;
                    }
                    $simple_menus[] = $page_menu;
                }
            }
            if (!empty($this->_custom_nav_links)) {
                foreach ($this->_custom_nav_links as $key => $menu) {
                    $simple_menus[] = $menu->render();
                }
            }

            usort($simple_menus, function ($a_c, $b_c) {
                if ($a_c['position'] == $b_c['position']) {
                    return 0;
                }
                return ($a_c['position'] < $b_c['position']) ? -1 : 1;
            });
            foreach ($simple_menus as $key => $menu) {
                if (!empty($menu['group'])) {
                    $this->addChildToGroup($saved_menus, $menu['group'], $menu);
                } else {
                    $saved_menus[] = $menu;
                }
            }
            usort($saved_menus, function ($a, $b) {

                if ($a['position'] == $b['position']) {
                    return 0;
                }
                return ($a['position'] < $b['position']) ? -1 : 1;
            });

            $nav_menu = [
                [
                    'label' => 'Dashboard',
                    'icon' => 'dashboard',
                    'position' => -1,
                    'path' => url($this->_route_path),
                ],
                ...$saved_menus
            ];
            if (config('laravue-panel.env') != 'local') {
                cache()->forever($nav_menu_cache_id, $nav_menu);
            }
        }

        $this->_nav_menu = $nav_menu;
        $user_menu_cache_id = 'lvp-user-menu-panel-' . $this->_id;

        $user_menu = config('laravue-panel.env') == 'local' ? [] : cache($user_menu_cache_id, []);
        if (empty($user_menu)) {
            if (!empty($this->login)) {
                $this->_user_menu[] = PanelNavLink::make('Profile', 'profile');
            }

            $user_menu = array_map(function ($menu) {
                return $menu->getNavMenu();
            }, $this->_user_menu);

            if (config('laravue-panel.env') != 'local') {
                cache()->forever($user_menu_cache_id, $user_menu);
            }
        }
    }
    public function configureGuards()
    {
        config([
            "auth.guards.$this->_id" => [
                'driver' => 'session',
                'provider' => $this->_id,
            ]
        ]);

        config([
            "auth.providers.$this->_id" => [
                'driver' => 'eloquent',
                'model' => $this->_auth_provider,
            ]
        ]);

    }
    public function addChildToGroup(&$array, $groupName, $newChild)
    {
        $groupExists = false;

        foreach ($array as &$group) {
            if (!empty($group['label']) && $group['label'] === $groupName) {
                $groupExists = true;
                if (!isset($group['children'])) {
                    $group['children'] = [];
                }
                unset($newChild['group']);
                $group['children'][] = $newChild;
                break;
            }
        }

        if (!$groupExists) {
            unset($newChild['group']);
            $menu_groups = $this->_menu_groups[$groupName] ?? [
                'position' => 0,
                'dismisable' => true,
            ];
            $array[] = [
                'label' => $groupName,
                'position' => $menu_groups['position'],
                'dismisable' => $menu_groups['dismisable'],
                'children' => [$newChild],
            ];
        }
    }
    private function setAuthSetting()
    {
        Auth::extend($this->_id, function ($app, $name, array $config) {
            $provider = Auth::createUserProvider($config['provider']);
            return new SessionGuard($name, $provider, $app['session.store']);
        });

        // Auth::provider($this->_id . '_provider', function ($app, array $config) {
        //     return new EloquentUserProvider($app->make(Hasher::class), $config['model']);
        // });
    }


}