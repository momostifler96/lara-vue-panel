<?php

namespace LVP\Defaults;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use LVP\Facades\LVPCurrentPanel;

class LoginPage
{

    public string $title = 'Login';
    public string $meta_title = 'Login';
    public string $meta_description = 'Login';
    public string $auth_provider = User::class;
    public array $_translations = [];

    public function __construct()
    {
        $locale = config('app.locale');
        $this->locale = $locale;
        $tr = require __DIR__ . './../Translations/' . $locale . '.php';
        $this->_translations = $tr;
    }

    public function index(Request $request)
    {
        /**
         * @var \LVP\Providers\PanelProvider $current_panel
         */
        $current_panel = app('lvp-current');
        $page_titles = $this->getPageTitles();
        $routes = [
            'index' => $current_panel->getPanelRouteName() . '.login',
            'store' => $current_panel->getPanelRouteName() . '.login.store',
        ];
        $labels = [
            'login' => lvp_translation('auth.login', $this->_translations),
            'identifiant' => lvp_translation('auth.identifiant', $this->_translations),
            'password' => lvp_translation('auth.password', $this->_translations),
            'remember_me' => lvp_translation('auth.remember_me', $this->_translations),
        ];
        // $titles = [
        //     'title'=>lvp_translation('auth.login_page_title', $this->_translations),
        //     'description'=>lvp_translation('auth.login_description', $this->_translations),
        // ];
        $props = compact('page_titles', 'routes', 'labels');
        return Inertia::render('LVP/LoginPage', $props);
    }
    public function login(Request $request)
    {
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
        $this->beforeLogin($request, $credentials);
        /**
         * @var \LVP\Modules\Panel\Panel $current_panel
         */
        $current_panel = app('lvp-current');
        $user = $current_panel->getAuthProvider()::where('email', $credentials['identifiant'])->first();
        if ($user && Hash::check($credentials['password'], $user->password)) {
            $this->beforeLogin($request, $credentials);
            auth($current_panel->getId())->login($user, $credentials['remember_me']);
            $this->afterLogin($request);
            return redirect()->intended(route($current_panel->getPanelRouteName()))->with('success', lvp_translation('auth.you_are_loged', $this->_translations));

        } else {
            return back()->withErrors([
                'identifiant' => lvp_translation('auth.credential_error', $this->_translations),
                'password' => lvp_translation('auth.credential_error', $this->_translations),
            ]);
        }
    }
    public function put(Request $request)
    {
        return back();
    }

    public function beforeLogin(Request $request, array &$credentials)
    {

    }
    public function afterLogin(Request $request)
    {

    }

    public function delete(Request $request)
    {
        return back();
    }
    public function getPageTitles()
    {
        return [
            'title' => lvp_translation('auth.login_page_title', $this->_translations),
            'meta_title' => lvp_translation('auth.login_page_title', $this->_translations),
            'meta_description' => lvp_translation('auth.login_page_description', $this->_translations),
        ];
    }

}
