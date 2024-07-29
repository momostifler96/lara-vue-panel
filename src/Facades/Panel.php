<?php

namespace LVP\Facades;

use App\Http\Resources\LVPResource;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\HandleInertiaRequests;
use Carbon\Carbon;
use LVP\Middlewares\LVPAuthMiddleware;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use LVP\Defaults\DashboardPage;
use LVP\Defaults\LVPLoginPage;
use LVP\Defaults\LVPRegisterPage;
use LVP\Middlewares\PanelGuestInertiaMiddleware;
use LVP\Middlewares\PanelInertiaMiddleware;

class Panel
{
    public static Panel $instance;
    public DashboardPage $dashboard;
    public LVPLoginPage $login;
    public string $_logout_route;
    public LVPRegisterPage $register;
    public $_id;
    protected $_base_route_name;
    protected $menu_groups = [
        'Blog' => [
            'position' => 0,
            'dismisable' => true,
        ],
        'Settings' => [
            'position' => 1,
            'dismisable' => true,
        ],
    ];
    public $_base_route_path;
    public Page $_login_page;
    public Page $_register_page;
    public Page $_profile_page;
    public string $_resources_path;
    public string $_pages_path;
    public string $_clusters_path;
    public string $_logo_path = '';
    /**
     * Summary of _custom_nav_links
     * @var CustomPanelNavLink[]
     */
    public array $_custom_nav_links;
    /**
     * Summary of _custom_nav_links
     * @var CustomPanelNavLink[]
     */
    public array $_user_menu = [];

    /**
     * The resources that should be loaded.
     *
     * @var Resource[]
     */
    public $_resources = [];

    /**
     * The resources that should be loaded.
     *
     * @var Page[]
     */
    public array $_pages = [];
    public array $_panels = [];
    private array $_auth_fields = ['identifiant', 'password'];
    private array $_middlewares = [];

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Set the base route name.
     * @param string $path
     * @return static
     */

    public function routeName(string $name)
    {
        $this->_base_route_name = $name;
        return $this;
    }
    /**
     * Summary of customNavLinks
     * @param CustomPanelNavLink[] $links
     * @return static
     */
    public function customNavLinks(array $links)
    {
        $this->_custom_nav_links = $links;
        return $this;
    }

    public function userMenu(array $links)
    {
        $this->_user_menu = $links;
        return $this;
    }
    public function routePath(string $name)
    {
        $this->_base_route_path = $name;
        return $this;
    }
    /**
     * Set id.
     * @param string $id
     * @return static
     */
    public function id(string $id)
    {
        $this->_id = $id;
        return $this;
    }

    /**
     * Set the resources directory path.
     * @param string $resources_path
     * @return static
     */
    public function loadResourcesFromPath(string|null $resources_path = null)
    {
        $this->_resources_path = $resources_path;
        return $this;
    }
    protected function dashboardWidgets(): array
    {
        return [];
    }

    protected function dashboardHeaderActions(): array
    {
        return [];
    }
    public function logoPath(string $logo_path)
    {
        $this->_logo_path = $logo_path;
        return $this;

    }
    public function getlogo(): string
    {
        return $this->_logo_path;
    }

    protected function dashboardTitle(): string
    {
        return 'Dashboard';
    }

    public function bootResources()
    {
        $filesystem = new Filesystem;

        $resourcesPath = $this->_resources_path ?? app_path('LVP/Resources');

        // Get all PHP files in the resources directory
        $resourceFiles = $filesystem->glob($resourcesPath . '/*.php');

        // Initialize an array to hold the command class names

        // Iterate over each file and extract the class name
        foreach ($resourceFiles as $file) {
            // Get the file contents
            $fileContents = $filesystem->get($file);

            // Use regex to extract the namespace and class name
            if (
                preg_match('/namespace (.+);/', $fileContents, $namespaceMatches) &&
                preg_match('/class (\w+)/', $fileContents, $classMatches)
            ) {
                $namespace = $namespaceMatches[1];
                $class = $classMatches[1];
                $resource_class = $namespace . '\\' . $class;
                /**
                 * @var Resource $resource_instance
                 */
                $resource_instance = new $resource_class($this);
                if (!$resource_instance->disabled) {
                    // dd($resource_instance);

                    // $resource_instance->settings($resource_instance);
                    // $resource_instance->makeNames();
                    $resource_instance->setup();
                    $this->_resources[] = $resource_instance;
                }
            }
        }
        return $this;
    }
    /**
     * Set the pages directory path.
     * @param string $pages_path
     * @return static
     */
    public function loadPagesFromPath(string|null $pages_path = null)
    {
        $this->_pages_path = $pages_path;
        return $this;
    }


    public function bootPages()
    {

        $filesystem = new Filesystem;

        $pagesPath = $this->_pages_path ?? app_path('LVP/Pages');

        // Get all PHP files in the pages directory
        $pageFiles = $filesystem->glob($pagesPath . '/*.php');
        $pageDirectoriesFiles = $filesystem->directories(app_path('LVP/Pages'));
        foreach ($pageDirectoriesFiles as $pageDirectory) {
            $pageFiles = array_merge($pageFiles, [$pageDirectory . '/IndexPage.php']);
        }

        // Initialize an array to hold the command class names
        // dd($pageFiles, $pageDirectoriesFiles, app_path('LVP/Pages'));
        // Iterate over each file and extract the class name
        foreach ($pageFiles as $file) {
            // Get the file contents
            $fileContents = $filesystem->get($file);

            // Use regex to extract the namespace and class name
            if (
                preg_match('/namespace (.+);/', $fileContents, $namespaceMatches) &&
                preg_match('/class (\w+)/', $fileContents, $classMatches)
            ) {
                $namespace = $namespaceMatches[1];

                $class = $classMatches[1];
                // if ($class == 'IndexPage') {
                //     $class_base_name = getDirectNameAfterNamespace($namespace, 'App\LVP\Pages');
                //     $page_class = $namespace . '\\' . $class_base_name . '\\' . $class;
                // } else {
                //     $page_class = $namespace . '\\' . $class;
                // }
                $page_class = $namespace . '\\' . $class;

                /**
                 * @var Page $page_instance
                 */
                $page_instance = new $page_class($this);
                if (!$page_instance->disabled) {
                    $page_instance->boot();
                    $this->_pages[] = $page_instance;
                }
            }
        }
        // dd('jjjjj');
        return $this;
    }
    public function makeRoutes()
    {
        // $this->dashboard->makeRoutes();

        foreach ($this->_resources as $key => $resource) {
            $resource->makeRoutes();
        }
        foreach ($this->_pages as $key => $page) {
            // dd($this->_pages);
            $page->makeRoutes();
        }
    }

    /**
     * Set the middlewares.
     * @param array $middlewares
     * @return static
     */
    public function middlewares(array $middlewares)
    {
        $this->_middlewares = $middlewares;
        return $this;
    }

    /**
     * Function to add a child to a group or create the group if it doesn't exist.
     *
     * @param array $array The array to modify.
     * @param string $groupName The name of the group.
     * @param array $newChild The new child to add.
     * @return void
     */
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
            $menu_groups = $this->menu_groups[$groupName] ?? [
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
    public function setLogout(string $route_name)
    {
        $this->_logout_route = $route_name;
    }
    public function getLogoutRoute()
    {
        return $this->_logout_route;
    }
    public function menuGroup(array $groups)
    {
        $this->menu_groups = $groups;
        return $this;
    }
    public function getData(): array
    {
        return [
            'name' => $this->_id,
            'index_path' => $this->_base_route_path,
            'logout_route' => $this->_logout_route,
            'user_profile_route' => $this->_base_route_name,
            'logo' => null,
            'theme' => null,
        ];
    }
    public function setupNavMenus()
    {

        $nav_menu = Cache::get('lvp-menus-' . $this->_id, []);
        // $nav_menu = [];
        if (empty($nav_menu)) {
            $saved_menus = [];
            $simple_menus = [];
            foreach ($this->_resources as $key => $resource) {
                $menu_group = $resource->getMenuGroup();
                $resource_menu = $resource->getNavMenu();
                if (!empty($menu_group)) {
                    $resource_menu['group'] = $menu_group;
                }
                $simple_menus[] = $resource_menu;
            }
            foreach ($this->_pages as $key => $page) {
                $menu_group = $page->getMenuGroup();
                $page_menu = $page->getNavMenu();
                if (!empty($menu_group)) {
                    $page_menu['group'] = $menu_group;
                }
                $simple_menus[] = $page_menu;
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
                // $resource_menu = $resource->getNavMenu();
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
                    'path' => url($this->_base_route_path),
                ],
                ...$saved_menus
            ];

            // dd($this->_id, $nav_menu);
            Cache::forever('lvp-menus-' . $this->_id, $nav_menu);
        }


        $user_menu = Cache::get('lvp-menus-user' . $this->_id, []);
        if (empty($user_menu)) {
            if (!empty($this->login)) {
                $this->_user_menu[] = new CustomPanelNavLink('Profile', 'profile');
            }
            $user_menu = array_map(function ($menu) {

                $menu->setSubpath($this->_base_route_path);
                return $menu->render();
            }, $this->_user_menu);
            Cache::forever('lvp-menus-user' . $this->_id, $user_menu);
        }


        return $nav_menu;
    }

    public static function getInstance()
    {
        if (empty(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }
    public static function panel(Panel $panel)
    {
    }
    public function getRouteName()
    {
        if (empty($this->_base_route_name)) {
            $base_name = class_basename(static::class);
            $panel_name = extractWordBefore($base_name, 'Panel');
            $this->_base_route_name = str($panel_name)->plural()->lower();
        }
        return $this->_base_route_name;
    }
    public function getId()
    {
        return $this->_id;
    }

    public function getRoutePath()
    {
        if (empty($this->_base_route_path)) {
            $base_name = class_basename(static::class);
            $panel_name = extractWordBefore($base_name, 'Panel');
            $this->_base_route_path = str($panel_name)->plural()->lower();
        }
        return $this->_base_route_path;
    }
    public function loginPage(LVPLoginPage|null $page = null)
    {
        if (!empty($page)) {
            $this->login = $page;
        } else {
            $this->login = new LVPLoginPage($this);
        }
        $middleware = LVPAuthMiddleware::class . ':' . $this->_id;
        if (!in_array($middleware, $this->_middlewares)) {
            $this->_middlewares[] = $middleware;
        }

        return $this;
    }
    public function profilePage(Page $page)
    {
        $this->_profile_page = $page;
        return $this;
    }
    public function registerPage(LVPRegisterPage|null $page = null)
    {
        if (!empty($page)) {
            $this->register = $page;
        } else {
            $this->register = new LVPRegisterPage($this);
        }
        $middleware = LVPAuthMiddleware::class . ':' . $this->_id;

        if (!in_array($middleware, $this->_middlewares)) {
            $this->_middlewares[] = $middleware;
        }
        return $this;
    }
    public function dashboardPage(DashboardPage $dashboard)
    {
        $this->dashboard = $dashboard;
        return $this;
    }
    public function setup()
    {
        $base_name = class_basename(static::class);
        $panel_name = extractWordBefore($base_name, 'Panel');
        $this->_id = !empty($this->_id) ? $this->_id : str($panel_name)->plural()->lower();
        $this->_base_route_name = !empty($this->_base_route_name) ? $this->_base_route_name : str($panel_name)->plural()->lower();
        $this->_base_route_path = !empty($this->_base_route_path) ? $this->_base_route_path : str($panel_name)->plural()->lower();
        // dd($this->_id, $panel_name);
        static::panel($this);
        $this->bootPages();
        $this->bootResources();
        $this->setupNavMenus();
    }

    public function boot()
    {

        if (empty($this->dashboard)) {
            $this->dashboard = new DashboardPage($this);
        }


        if (!empty($this->login)) {
            Route::middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                PanelGuestInertiaMiddleware::class
            ])->group(function () {
                $this->login->setup();
            });
        }
        if (!empty($this->register)) {
            $this->register->setPanel($this);

            Route::middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                PanelGuestInertiaMiddleware::class
            ])->group(function () {
                $this->register->setup();
            });
        }
        foreach ($this->_resources as $resource) {
            $resource->setupCallbacks();
        }

        Route::group([
            'middleware' => [
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class
            ],
            'prefix' => $this->_base_route_path . '/rest',
            'as' => $this->_base_route_name . '.rest.'
        ], function () {
            Route::get('user-notifications', function (Request $request) {
                return response()->json(auth()->user()->unreadNotifications()->latest()->get()->map(function ($notif) {
                    return [
                        'date' => Carbon::parse($notif->create_at)->diffForHumans(),
                        'id' => $notif->id,
                        'title' => $notif->data['title'],
                        'body' => $notif->data['body'],
                        'status' => $notif->data['status'] ?? 'info',
                    ];
                }));
            })->name('notifications');
            Route::post('read-notifications', function (Request $request) {
                auth()->user()->unreadNotifications()->where('id', $request->id)->first()->markAsRead();
                return response()->json(['status' => 'success']);
            })->name('read-notifications');
        });


        Route::group([
            'prefix' => $this->_base_route_path,
            'as' => $this->_base_route_name . '.',
            'middleware' => [
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                ...$this->_middlewares,
                PanelInertiaMiddleware::class
            ]
        ], function () {
            $this->dashboard->setup();
            $this->dashboard->headerActions($this->dashboardHeaderActions());
            $this->dashboard->widgets($this->dashboardWidgets());
            // $this->dashboard->title($this->dashboardTitle());

            $this->makeRoutes();
        });
    }
}
