<?php
namespace LVP\Modules\Panel\Features;

use LVP\Defaults\LoginPage;
use LVP\Middlewares\PanelAuthMiddleware;
use LVP\Middlewares\PanelGuestInertiaMiddleware;
use LVP\Middlewares\PanelGuestMiddleware;

trait Fluents
{
    #fluent methods
    public function id(string $id)
    {
        $this->_id = $id;
        if (empty($this->_route_name)) {
            $this->_route_name = $id;
        }
        if (empty($this->_route_path)) {
            $this->_route_path = $id;
        }
        return $this;
    }

    public function customNavLinks(array $nav_links)
    {
        $this->_custom_nav_links = $nav_links;
        return $this;
    }
    public function routeName(string $route_name)
    {
        $this->_route_name = $route_name;
        return $this;
    }

    public function routePath(string $route_path)
    {
        $this->_route_path = $route_path;
        return $this;
    }
    public function login()
    {
        array_unshift($this->_panel_middlewares, PanelAuthMiddleware::class . ':' . $this->_id);
        array_unshift($this->_guest_middlewares, PanelGuestMiddleware::class . ':' . $this->_id);
        $this->_login_page_class = LoginPage::class;

        return $this;
    }
    public function loadResourcesFromPath(string $path)
    {
        $this->_resources_path = $path;
        return $this;
    }
    public function loadPagesFromPath(string $path)
    {
        $this->_pages_path = $path;
        return $this;
    }
    public function dashboard(string $class)
    {
        $this->_dashboard_class = $class;
        return $this;
    }
    public function logo(string $url)
    {
        $this->_logo = $url;
        return $this;
    }

    public function authModelProvider(string $model)
    {
        $this->_auth_provider = $model;
        return $this;
    }
    public function middleware(array $middlewares)
    {
        // array_push($this->_middlewares, ...$middlewares);
        return $this;
    }

    /**
     * Summary of menuGroups
     * @param \LVP\Support\PanelMenuGroup[] $groups
     * @return static
     */
    public function menuGroups(array $groups)
    {
        $this->_menu_groups = $groups;
        return $this;
    }
}