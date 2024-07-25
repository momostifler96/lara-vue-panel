<?php

namespace LVP\Defaults;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use LVP\Facades\Page;
use LVP\Facades\Panel;
use LVP\Middlewares\LVPAuthMiddleware;
use LVP\Middlewares\LVPGuestMiddleware;

class LVPRegisterPage
{
    private Panel $panel;
    private string $_route_name = "register";
    private string $_route_path = "register";
    private string $_page_title = "Register";
    private string $_meta_title = "Register";
    private string $_submit_button_label = "Register";
    private string $_form_title = "Register";

    private string $_form_sub_title = "Register";
    private string $_view_path = "Auth/Register";

    private array $_middleware = [];
    private array $_fields = [];

    public function __construct(Panel $panel)
    {
        $this->panel = $panel;
    }

    public function setInfo(RegisterPage $page)
    {
    }
    public function middleware(array $middlewares)
    {
        $this->_middleware = $middlewares;
        return  $this;
    }
    public function pageTitle(string $title)
    {
        $this->_page_title = $title;
        return  $this;
    }
    public function metaTitle(string $title)
    {
        $this->_meta_title = $title;
        return  $this;
    }
    public function fields(array $fields)
    {
        $this->_fields = $fields;
        return  $this;
    }
    public function formTitle(string $title)
    {
        $this->_form_title = $title;
        return  $this;
    }
    public function formSubTitle(string $title)
    {
        $this->_form_sub_title = $title;
        return  $this;
    }
    public function routeName(string $name)
    {
        $this->_route_name = $name;
        return  $this;
    }
    public function routePath(string $path)
    {
        $this->_route_path = $path;
        return  $this;
    }
    public function setPanel(Panel $panel)
    {
        $this->panel = $panel;
        return  $this;
    }

    public function beforRenderPage(Request $request): array
    {
        return [];
    }
    public function beforRegister(Request $request, array $credentials): array
    {
        return [];
    }
    public function setup()
    {
        Route::group(['prefix' => $this->panel->getRoutePath(), 'as' => $this->panel->getRouteName() . '.', 'middleware' => LVPGuestMiddleware::class . ':' . $this->panel->getId(), ...$this->_middleware], function () {
            Route::get('/' . $this->_route_path, [static::class, 'index'])->name($this->_route_name);
            Route::post('/' . $this->_route_path, [static::class, 'store'])->name($this->_route_name . '.store');
        });
    }

    public function index(Request $request)
    {
        $custom_data = $this->beforRenderPage($request);
        $page_title = $this->_page_title;
        $meta_title = $this->_meta_title;
        $form_title = $this->_form_title;
        $form_sub_title = $this->_form_sub_title;
        $fields = $this->_fields;
        $submit_button_label = $this->_submit_button_label;
        $page_routes = [
            'index' => $this->_route_name,
            'store' => $this->_route_name . '.store',
        ];
        return Inertia::render('LVP/' . $this->_view_path, compact('custom_data', 'page_routes', 'page_title', 'meta_title', 'form_title', 'form_sub_title', 'fields', 'submit_button_label'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'min:5', 'max:30', 'email'],
            'password' => ['required', 'min:5', 'max:30'],
        ]);
        $user_data = $request->only($this->_fields);
        try {
            $user = User::create($user_data);
            auth()->login($user);
            return to_route($this->panel->getRouteName())->with('success', 'You are registered');
        } catch (\Throwable $th) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
                'password' => 'The provided credentials do not match our records.',
            ]);
        }
    }
}
