<?php

namespace LVP\Facades;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use LVP\Defaults\LoginPage;
use LVP\Defaults\RegisterPage;
use LVP\Middlewares\PanelInertiaMiddleware;

class Page
{
    private static Page $_instance;
    private $route_name;
    private int $menu_position = 0;
    public bool $disabled = false;
    private bool $has_full_path = false;
    private bool $has_rest_route = false;
    private bool $is_sub_page = false;
    private string $parent_route_name;
    private string $parent_route_path;
    private string $page_title;
    private string $meta_title = '';
    private string $meta_description = '';
    private string $slug;
    private array $http_methods = ['get', 'post'];
    /**
     * Summary of sub_pages
     * @var Page[]
     */
    private array $sub_pages_class;
    /**
     * Summary of sub_routes
     * @var SubRoute[]
     */
    private array $sub_routes;
    /**
     * Summary of sub_pages
     * @var Page[]
     */
    private array $sub_pages_instances;
    public string $menu_icon = 'stack';

    protected string $navigation_label;
    protected bool $show_on_navigation = true;
    private string $view_path = 'LVP/Pages/BasePage';
    private string $menu_group;

    protected array $_middlewares = [];
    protected array $index_middlewares = [];
    protected array $post_middlewares = [];
    protected array $put_middlewares = [];
    protected array $delete_middlewares = [];
    /**
     * Create a new class instance.
     */


    public function __construct(Panel|Page $parent)
    {
        $this->parent_route_name = $parent->getRouteName();
        $this->parent_route_path = $parent->getRoutePath();

        if ($parent instanceof Page) {
            $this->is_sub_page = true;
        }
    }
    public function getMenuLabel()
    {
        return empty($this->navigation_label) ? class_basename(get_called_class()) : $this->navigation_label;
    }

    protected function settings(Page $page)
    {
    }
    protected function setMenuLabel(string $label)
    {
        $this->navigation_label = $label;
        return $this;
    }

    /**
     * Summary of addSubRoutes
     * @param SubRoute[] $routes
     * @return static
     */
    protected function addSubRoutes(array $routes)
    {
        $this->sub_routes = $routes;
        return $this;
    }
    protected function hasFullPath()
    {
        $this->has_full_path = true;
        return $this;
    }

    protected function hasRestRoute()
    {
        $this->has_rest_route = true;
        return $this;
    }

    protected function notShowInMenu()
    {

        $this->show_on_navigation = false;
        return $this;
    }
    protected function setMenuIcon(string $icon)
    {

        $this->menu_icon = $icon;
        return $this;
    }
    protected function setMenuPosition(string $icon)
    {
        $this->menu_icon = $icon;
        return $this;
    }
    protected function disable(bool $disable = true)
    {
        $this->disabled = $disable;
        return $this;
    }
    protected function menuGroup(string $group)
    {

        $this->menu_group = $group;
        return $this;
    }
    protected function setPageTitle(string $title)
    {

        $this->page_title = $title;
        return $this;
    }
    protected function setPageSlug(string $slug)
    {

        $this->slug = $slug;
        return $this;
    }
    protected function setMetaTitle(string $title)
    {

        $this->meta_title = $title;
        return $this;
    }
    protected function setMetaDescription(string $description)
    {
        $this->meta_description = $description;
        return $this;
    }
    protected function setViewPath(string $path)
    {
        $this->view_path = $path;
        return $this;
    }
    protected function setRouteName(string $name)
    {
        $this->route_name = $name;
        return $this;
    }
    protected function hasPostRequest()
    {

        $this->http_methods[] = 'post';
        return $this;
    }
    protected function hasPutRequest()
    {

        $this->http_methods[] = 'put';
        return $this;
    }
    protected function hasDeleteRequest()
    {

        $this->http_methods[] = 'delete';
        return $this;
    }
    protected function middlewares(array $middlewares)
    {

        $this->_middlewares[] = $middlewares;
        return $this;
    }
    protected function indexMiddlewares(array $middlewares)
    {

        $this->index_middlewares = $middlewares;
        return $this;
    }
    protected function postMiddlewares(array $middlewares)
    {
        $this->post_middlewares = $middlewares;
        return $this;
    }
    protected function putMiddlewares(array $middlewares)
    {

        $this->put_middlewares = $middlewares;
        return $this;
    }
    protected function deleteMiddlewares(array $middlewares)
    {

        $this->delete_middlewares = $middlewares;
        return $this;
    }
    /**
     *  Add sub pages to page
     * @param Page[] $pages
     * @return static
     */
    protected function addSubPages(array $pages)
    {
        $this->sub_pages_class = $pages;
        return $this;
    }

    public function getNavMenu()
    {
        return [
            'label' => $this->getMenuLabel(),
            'position' => $this->menu_position,
            'icon' => $this->getMenuIcon(),
            'path' => url($this->geFullRoutepath()),
        ];
    }
    public function getMenuGroup(): null|string
    {
        return $this->menu_group ?? null;
    }
    public function getMenuIcon()
    {
        return $this->menu_icon;
    }
    public function getRoutePath()
    {
        return $this->geFullRoutepath('full_path');
    }
    public function getBaseRoute($type = 'full_path')
    {
        return $type == 'full_path' ? $this->slug : $this->parent_route_name . '.' . $this->route_name;
    }
    public function geFullRoutepath($type = 'full_path')
    {
        return $type == 'full_path' ? '/' . $this->parent_route_path . '/' . $this->slug : '/' . $this->parent_route_path . '.' . $this->route_name;
    }
    public function getCurrentClassNamespace()
    {
        return (new \ReflectionClass($this))->getNamespaceName();
    }
    public function loadFormFields($action)
    {
        $fields = [
            'form_render' => [],
            'form_data' => [],
        ];

        foreach ($this->formFields() as $key => $field) {
            if (!$field->isHiddenOnCreate()) {
                $fields['form_render'][] = $field->render($action);
                $fields['form_data'][$field->field()] = $field->defaultValue();
            }
        }

        return $fields;
    }
    public function makeNames()
    {
        $base_name = class_basename(get_called_class());
        $class_base_name = extractWordBefore($base_name, 'Page');
        if ($class_base_name == 'Index') {
            $current_class_namespace = $this->getCurrentClassNamespace();
            $class_base_name = getDirectNameAfterNamespace($current_class_namespace, 'App\LVP\Pages');
        }
        $class_base_name_lower = str($class_base_name)->kebab();
        $class_base_name_camel = str(!empty($this->navigation_label) ? $this->navigation_label : $class_base_name)->kebab()->replace('-', ' ')->ucfirst();

        if ($this->show_on_navigation && empty($this->navigation_label)) {
            $this->navigation_label = $class_base_name_camel;
        }
        $this->page_title = !empty($this->page_title) ? $this->page_title : $class_base_name_camel;
        $this->meta_title = !empty($this->meta_title) ? $this->meta_title : $class_base_name_camel;
        $this->slug = !empty($this->slug) ? $this->slug : $class_base_name_lower;
        $this->route_name = !empty($this->route_name) ? $this->route_name : $class_base_name_lower;
    }
    public function boot()
    {
        $this->settings($this);
        $this->makeNames();
        if (!empty($this->sub_pages_class)) {
            $this->bootSubPages();
        }
    }

    public function bootSubPages()
    {
        $this->sub_pages_instances = array_map(function ($page) {
            $sub_page = new $page($this);
            $sub_page->boot();
            return $sub_page;
        }, $this->sub_pages_class);
    }

    public static function getInstance(Panel $panel)
    {
        if (empty(static::$_instance)) {
            static::$_instance = new static($panel);
        }
        return static::$_instance;
    }
    public function getPageTitles()
    {
        // dd($this);
        return [
            'title' => $this->page_title,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
        ];
    }
    public function getPageRoutes()
    {
        $route_name = $this->getRouteName();
        $routes = [];
        foreach ($this->http_methods as $method) {
            $rn = $method == 'get' ? 'index' : $method;
            $routes[$rn] = $route_name . '.' . $rn;
        }
        if (!empty($this->sub_routes)) {
            foreach ($this->sub_routes as $route) {
                $routes[$route->getRouteName()] = $route_name . '.' . $route->getRouteName();
            }
        }

        if ($this->has_rest_route) {
            $routes['rest'] = $route_name . '.rest';
        }
        return $routes;
    }
    public function getRouteName()
    {
        return $this->parent_route_name . '.' . $this->route_name;
    }

    public function getPageData(Request $request)
    {
        return $request->all();
    }
    protected function beforeContent(Request $request): array
    {
        return [];
    }
    protected function afterContent(Request $request): array
    {
        return [];
    }


    public function getPageActions(Request $request)
    {
        return [];
    }
    // http request hooks
    public function onGetRequest(Request $request, $sub_path)
    {
        return [];
    }
    public function onPostRequest(Request $request)
    {
        return [];
    }
    public function onPutRequest(Request $request)
    {
        return [];
    }
    public function onDeleteRequest(Request $request)
    {
        return [];
    }
    public function makeRoutes()
    {

        Route::group(['prefix' => $this->slug, 'as' => $this->route_name . '.', 'middleware' => $this->_middlewares], function () {
            if ($this->has_rest_route) {
                Route::get('/rest', function (Request $request) {
                    return $this->restRequest($request);
                })->withoutMiddleware(PanelInertiaMiddleware::class)->name('rest');
            }
            if ($this->has_full_path) {
                Route::get('/{sub_route?}', function (Request $request, $sub_path = null) {
                    return $this->index($request, $sub_path);
                })->where('sub_route', '.*')->middleware($this->index_middlewares)->name('index');
            } else {
                Route::get('/', function (Request $request) {
                    return $this->index($request);
                })->middleware($this->index_middlewares)->name('index');
            }

            if (in_array('post', $this->http_methods)) {
                Route::post('/', function (Request $request) {
                    return $this->post($request);
                })->middleware($this->post_middlewares)->name('post');
            }
            if (in_array('put', $this->http_methods)) {
                Route::put('/', function (Request $request) {
                    return $this->put($request);
                })->middleware($this->put_middlewares)->name('put');
            }
            if (in_array('delete', $this->http_methods)) {
                Route::put('/', function (Request $request) {
                    return $this->destroy($request);
                })->middleware($this->delete_middlewares)->name('delete');
            }
            if (!empty($this->sub_routes)) {
                foreach ($this->sub_routes as $route) {
                    Route::match(
                        $route->getMethod(),
                        $route->getPath(),
                        fn(Request $request) => $route->execController($request)
                    )
                        ->middleware($route->getMiddlewares())
                        ->name($route->getRouteName());
                }
            }
        });
        if (!empty($this->sub_pages_instances)) {
            Route::group(['prefix' => $this->slug, 'as' => $this->route_name . '.', 'middleware' => $this->_middlewares], function () {
                foreach ($this->sub_pages_instances as $page) {
                    $page->makeRoutes();
                }
            });
        }
        // dd($this->sub_pages_instances);
        // dump($this->getRouteName());

    }
    private function renderWidgets(array $widgets)
    {
        // dd($widgets);
        $_widgets = [];
        foreach ($widgets as $key => $_widget) {
            $_widgets[] = $_widget->render();
        }
        return $_widgets;
    }
    public function index(Request $request, $sub_path = null)
    {
        $custom_data = $this->onGetRequest($request, $sub_path);
        $before_content_widgets = $this->renderWidgets($this->beforeContent($request));
        $after_content_widgets = $this->renderWidgets($this->afterContent($request));
        $page_actions = $this->getPageActions($request);
        // dd($this);

        $page_titles = $this->getPageTitles();
        // dd($page_title);
        $page_data = $this->getPageData($request);
        $route_names = $this->getPageRoutes();
        $route_paths = $this->geFullRoutepath();
        $page_path = $this->view_path === 'LVP/Pages/BasePage' ? 'LVP/Pages/BasePage' : $this->view_path;
        return Inertia::render($page_path, compact('before_content_widgets', 'after_content_widgets', 'page_titles', 'route_paths', 'page_data', 'custom_data', 'route_names'));
    }
    public function post(Request $request)
    {
        try {
            $this->onPostRequest($request);
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            Session::flash('error', $th->getMessage());
        }
        return back();
    }
    public function restRequest(Request $request)
    {
        $response = $this->onRestRequest($request);
        return response()->json(['rest_data' => $response]);
    }
    public function onRestRequest(Request $request)
    {
        return [];
    }
    public function put(Request $request)
    {
        $this->onPutRequest($request);
        return back();
    }
    public function destroy(Request $request)
    {
        $this->onDeleteRequest($request);
        return back();
    }
}
