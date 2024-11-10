<?php
namespace LVP\Modules\Page\Features;

use Illuminate\Http\Request;
use LVP\Modules\Panel\Panel;

trait Geters
{
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
            'index' => $this->route_name,
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

    public function getMenuLabel()
    {
        return $this->menu_label;
    }
    public function getNavMenu()
    {
        return [
            'label' => $this->getMenuLabel(),
            'position' => $this->menu_position,
            'icon' => $this->menu_icon,
            'path' => '/' . $this->slug,
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


    public function currentUser(): \Illuminate\Foundation\Auth\User|null
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

    protected function setAuthtUser(Panel $panel_provider)
    {
        $panel_provider = app('lvp-current');
        $this->current_user = $panel_provider->user();
    }



    public function getRouteName()
    {
        return $this->parent_route_name . '.' . $this->route_name;
    }

    public function getPageData(Request $request)
    {
        return $request->all();
    }
    public function canShowMenu(): bool
    {
        return $this->show_in_menu;
    }
}
