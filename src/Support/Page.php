<?php

namespace LVP\Support;

use Illuminate\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use LVP\Defaults\LoginPage;
use LVP\Defaults\RegisterPage;
use LVP\Middlewares\PanelInertiaMiddleware;
use LVP\Providers\PanelProvider;

class Page
{
    protected int $menu_position = 0;
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
    public string $menu_icon = 'stack';
    protected string $view_path = 'LVP/PageTemplate';
    protected string $menu_group;
    protected string $navigation_label;

    protected \Illuminate\Foundation\Auth\User $current_user;
    protected bool $show_on_navigation = true;


    protected array $_middlewares = [];
    protected array $index_middlewares = [];
    protected array $post_middlewares = [];
    protected array $put_middlewares = [];
    protected array $delete_middlewares = [];


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



    #Geters
    public function getRoute($route = 'all'): array|string
    {
        $routes = [
            'index' => $this->slug . '.index',
            'store' => $this->slug . '.store',
            'create' => $this->slug . '.create',
            'edit' => $this->slug . '.edit',
            'update' => $this->slug . '.update',
            'delete' => $this->slug . '.delete',
        ];

        if ($route == 'all') {
            return $routes;
        } else {
            return $routes[$route];
        }
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
            'icon' => $this->menu_icon,
            'path' => url($this->slug),
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

    public function currentUser(): \Illuminate\Foundation\Auth\User
    {
        if (empty($this->current_user)) {
            /**
             * @var \LVP\Providers\PanelProvider $current_panel
             */
            $current_panel = app('lvp-current');
            $this->current_user = auth($current_panel->getId())->user();
        }

        return $this->current_user;
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

    protected function setAuthtUser(PanelProvider $panel_provider)
    {
        $panel_provider = app('lvp-current');
        $this->panel_provider = $panel_provider->user();
    }

    public function boot(PanelProvider $panel_provider)
    {

        $this->setAuthtUser($panel_provider);
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

    protected function setupPanel(PanelProvider $panel_provider)
    {
        $this->_panel_route_name = $panel_provider->getPanelRouteName();
        $this->_panel_route_path = $panel_provider->getPanelRoutePath();
        $this->_local = $panel_provider->getPanelLocal();
        $this->_middlewares = $panel_provider->getMiddlewares();

    }

    public function makeNames()
    {
        $base_name = class_basename(get_called_class());
        $class_base_name = str(extractWordBefore($base_name, 'Page'));
        $class_base_name_plural = $class_base_name->plural();

        if (empty($this->label)) {
            $this->label = $class_base_name->kebab()->replace('-', ' ')->lower()->ucfirst();
        }
        if (empty($this->short_label)) {
            $this->short_label = $this->label;
        } else {
            $this->short_label = str($this->short_label)->kebab()->replace('-', ' ')->lower()->ucfirst();
        }

        if ($this->show_on_navigation) {
            $this->navigation_label = $this->label;
        }

        $this->page_title = ucfirst(!empty($this->page_title) ? $this->page_title : $this->label);
        $this->meta_title = ucfirst(!empty($this->meta_title) ? $this->meta_title : $this->label);
        $this->slug = $this->_panel_route_path . '/' . str(!empty($this->slug) ? $this->slug : $class_base_name)->kebab()->lower()->toString();
        $this->route_name = $this->_panel_route_name . '.' . str(!empty($this->route_name) ? $this->route_name : $class_base_name)->kebab()->lower()->toString();
    }

    public function registerRoutes()
    {

        Route::middleware($this->_middlewares)
            ->group(function () {
                if ($this->has_full_path) {
                    Route::get($this->slug . '/{slug?}', fn(Request $request) => $this->index($request))->where('slug', '.*')->middleware($this->index_middlewares)->name($this->route_name);
                } else {
                    Route::get($this->slug, fn(Request $request) => $this->index($request))->middleware($this->index_middlewares)->name($this->route_name);
                }
                if (in_array('post', $this->http_methods)) {
                    Route::post($this->slug, fn(Request $request) => $this->post($request))->middleware($this->post_middlewares)->name($this->route_name . '.post');
                }
                if (in_array('put', $this->http_methods)) {
                    Route::put($this->slug, fn(Request $request) => $this->put($request))->middleware($this->put_middlewares)->name($this->route_name . '.put');
                }
                if (in_array('delete', $this->http_methods)) {
                    Route::delete($this->slug, fn(Request $request) => $this->delete($request))->middleware($this->delete_middlewares)->name($this->route_name . '.delete');
                }
            });
    }
    public function register(PanelProvider $panelProvider)
    {
        $this->setupPanel($panelProvider);
        $this->makeNames();
        $this->registerRoutes();
        // $this->setupPanel($panelProvider);
    }
    public function registerNames()
    {
        $base_name = class_basename(get_called_class());
        $class_base_name = extractWordBefore($base_name, 'Resource');

        $class_base_name_plural = str($class_base_name)->plural();

        if (empty($this->label)) {
            $this->label = $class_base_name_plural->kebab()->replace('-', ' ')->lower()->ucfirst();
        }
        if (empty($this->short_label)) {
            $this->short_label = $this->label;
        }

        $this->short_label = str($this->short_label)->lower();

        if ($this->show_on_navigation) {
            $this->navigation_label = $this->label;
        }

        $this->page_title = ucfirst(!empty($this->page_title) ? $this->page_title : $this->label);
        $this->meta_title = ucfirst(!empty($this->meta_title) ? $this->meta_title : $this->label);

        $this->slug = $this->_panel_route_path . '/' . str(!empty($this->slug) ? $this->slug : $class_base_name_plural)->kebab()->lower()->toString();
        $this->route_name = $this->_panel_route_name . '.' . str(!empty($this->route_name) ? $this->route_name : $class_base_name_plural)->kebab()->lower()->toString();
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
    public function getViewPath()
    {
        return $this->view_path;
    }
    public function getPageTitles()
    {
        return [
            'title' => $this->page_title,
            'short_title' => $this->short_label,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
        ];
    }
    public function getRouteNames()
    {
        $routes = [
            'index' => $this->route_name . '.index',
        ];

        if (in_array('post', $this->http_methods)) {
            $routes[] = 'post';
        }

        if (in_array('put', $this->http_methods)) {
            $routes[] = 'put';
        }

        if (in_array('delete', $this->http_methods)) {
            $routes[] = 'delete';
        }

        return $routes;
    }
    public function index(Request $request, $sub_path = null)
    {
        $before_content_widgets = $this->renderWidgets($this->beforeContent($request));
        $after_content_widgets = $this->renderWidgets($this->afterContent($request));
        $page_titles = $this->getPageTitles();
        $route_names = $this->getRouteNames();
        $page_data = $this->onGetRequest($request, $sub_path);
        $props = compact('before_content_widgets', 'after_content_widgets', 'page_data', 'page_titles', 'route_names');
        return Inertia::render($this->view_path, $props);
    }
    public function post(Request $request)
    {
        try {
            $this->onPostRequest($request);
        } catch (\Throwable $th) {
            Session::flash('error', $th->getMessage());
        }
        return back();
    }


    public function put(Request $request)
    {
        $this->onPutRequest($request);
        return back();
    }

    public function delete(Request $request)
    {
        $this->onDeleteRequest($request);
        return back();
    }

    protected function onGetRequest(Request $request, $sub_path)
    {
        return [];
    }
    protected function onPostRequest(Request $request)
    {
        return [];
    }
    protected function onPutRequest(Request $request)
    {
        return [];
    }
    protected function onDeleteRequest(Request $request)
    {
        return [];
    }
}
