<?php

namespace LVP\Modules\Panel\Features;

trait Geters
{
    public function getId(): string
    {
        return $this->_id;
    }
    public function getPanelRouteName(): string
    {
        return $this->_route_name;
    }

    public function getPanelRoutePath(): string
    {
        return $this->_route_path;
    }
    public function getPanelLocal(): string
    {
        return $this->_local;
    }
    private function getMiddlewares(): array
    {
        return $this->_middlewares;
    }
    private function getGestMiddlewares(): array
    {
        return $this->_guest_middlewares;
    }
    public function getLogo()
    {
        return $this->_logo;
    }

    public function getData()
    {
        return [
            'id' => $this->_id,
            'base_route' => $this->_resources_path,
        ];
    }
    public function getAuthProvider()
    {
        return $this->_auth_provider;
    }

    public function getNavMenu()
    {
        return $this->_nav_menu;
    }
    public function getUserMenu()
    {
        return $this->_user_menu;
    }
}