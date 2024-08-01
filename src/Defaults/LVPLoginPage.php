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

class LVPLoginPage
{
    private Panel $panel;
    private string $_route_name = "login";
    private string $_route_path = "login";
    private string $_page_title = "Login";
    private string $_meta_title = "Login";
    private string $_submit_button_label = "Login";
    private string $_form_title = "Login";

    private string $_form_sub_title = "Login";
    private string $_view_path = "Auth/Logi";

    private array $_middleware = [];
    private array $_fields = [];

    public function __construct(Panel $panel)
    {
        $this->panel = $panel;
    }


    public function middleware(array $middlewares)
    {
        $this->_middleware = $middlewares;
        return $this;
    }
    public function pageTitle(string $title)
    {
        $this->_page_title = $title;
        return $this;
    }
    public function metaTitle(string $title)
    {
        $this->_meta_title = $title;
        return $this;
    }
    public function fields(array $fields)
    {
        $this->_fields = $fields;
        return $this;
    }
    public function formTitle(string $title)
    {
        $this->_form_title = $title;
        return $this;
    }
    public function formSubTitle(string $title)
    {
        $this->_form_sub_title = $title;
        return $this;
    }
    public function routeName(string $name)
    {
        $this->_route_name = $name;
        return $this;
    }
    public function routePath(string $path)
    {
        $this->_route_path = $path;
        return $this;
    }
    public function getRoutesNames()
    {
        return [
            'index' => $this->panel->getRouteName() . '.' . $this->_route_name,
            'store' => $this->panel->getRouteName() . '.' . $this->_route_name . '.store',
        ];
    }
    public function getRoutesPaths()
    {
        return [
            'index' => $this->panel->getRoutePath() . '/' . $this->_route_path,
            'store' => $this->panel->getRoutePath() . '/' . $this->_route_path,
        ];
    }
    public function setPanel(Panel $panel)
    {
        $this->panel = $panel;
        return $this;
    }

    public function beforRenderPage(Request $request): array
    {
        return [];
    }
    public function beforLogin(Request $request, array $credentials): array
    {
        return [];
    }
    public function beforLogout(Request $request): array
    {
        return [];
    }
    public function setup()
    {
        $page = null;
        $this->panel->setLogout($this->panel->getRouteName() . '.logout');
        Route::group(['prefix' => $this->panel->getRoutePath(), 'as' => $this->panel->getRouteName() . '.', 'middleware' => LVPGuestMiddleware::class . ':' . $this->panel->getId(), ...$this->_middleware], function () {
            Route::get('/' . $this->_route_path, function (Request $request) {
                return $this->index($request);
            })->name($this->_route_name);
            Route::post('/' . $this->_route_path, function (Request $request) {
                return $this->login($request);
            })->name($this->_route_name . '.store');
        });
        Route::post($this->panel->getRoutePath() . '/logout', function (Request $request) {
            return $this->logout($request);
        })->name($this->panel->getRouteName() . '.' . 'logout')->middleware([LVPAuthMiddleware::class . ':' . $this->panel->getId()]);
    }

    public function index(Request $request)
    {
        // dd($this);
        $custom_data = $this->beforRenderPage($request);
        $page_title = $this->_page_title;
        $meta_title = $this->_meta_title;
        $form_title = $this->_form_title;
        $form_sub_title = $this->_form_sub_title;
        $fields = $this->_fields;
        $submit_button_label = $this->_submit_button_label;
        $page_routes = $this->getRoutesNames();
        // $view_path = 'LVP/' . $this->_view_path;
        $view_path = 'LVP/LoginPageTemplate';
        return Inertia::render($view_path, compact('custom_data', 'page_routes', 'page_title', 'meta_title', 'form_title', 'form_sub_title', 'fields', 'submit_button_label'));
    }
    public function login(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'identifiant' => [
                'required',
                'min:5',
                'max:30',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/^[a-zA-Z0-9]+$/', $value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $fail('The ' . $attribute . ' must be a valid alphanumeric string or a valid email address.');
                    }
                }
            ],
            'password' => ['required', 'min:5', 'max:30'],
        ]);
        $credentials = $request->only('identifiant', 'password', 'remember_me');
        $user = User::where('email', $credentials['identifiant'])->first();
        if ($user && Hash::check($credentials['password'], $user->password)) {
            $this->beforLogin($request, $credentials);
            auth()->login($user, $credentials['remember_me']);
            return to_route($this->panel->getRouteName() . '.login')->with('success', 'You are logind');
        } else {
            return back()->withErrors([
                'identifiant' => 'The provided credentials do not match our records.',
                'password' => 'The provided credentials do not match our records.',
            ]);
        }
    }
    public function logout(Request $request)
    {
        // dd($request);
        $this->beforLogout($request);
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();
        return to_route($this->panel->getRouteName() . '.' . $this->_route_name)->with('success', 'You are logind');
    }
}
