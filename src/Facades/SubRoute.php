<?php

namespace LVP\Facades;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use LVP\Defaults\LoginPage;
use LVP\Defaults\RegisterPage;
use LVP\Enums\HttpMethod;
use LVP\Middlewares\PanelInertiaMiddleware;

class SubRoute
{
    private string $route_name;
    private string $path;
    private string $method;
    private $controller;
    private array $_middlewares = [];



    public function __construct(string $name, HttpMethod $method, callable $controller)
    {
        $this->route_name = $name;
        $this->path = $name;
        $this->method = $method->value;
        $this->controller = $controller;
    }


    public static function make(string $name, HttpMethod $method, callable $controller)
    {
        return new self($name, $method, $controller);
    }
    public  function setPath(string $path)
    {
        $this->path = $path;
        return $this;
    }


    protected function middleware(array $middlewares)
    {
        $this->_middlewares = $middlewares;
        return $this;
    }


    public function getRouteName()
    {
        return $this->route_name;
    }
    public function getPath()
    {
        return $this->path;
    }
    public function getMethod()
    {
        return $this->method;
    }
    public function getMiddlewares()
    {
        return $this->_middlewares;
    }
    public function execController(...$args)
    {
        return call_user_func_array($this->controller, $args);
    }
}
