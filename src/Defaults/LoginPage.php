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
        $props = compact('page_titles', 'routes');
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

        $this->afterLogin($request);
        /**
         * @var \LVP\Modules\Panel\Panel $current_panel
         */
        $current_panel = app('lvp-current');
        $credentials = $request->only('identifiant', 'password', 'remember_me');
        $user = $current_panel->getAuthProvider()::where('email', $credentials['identifiant'])->first();
        if ($user && Hash::check($credentials['password'], $user->password)) {
            $this->beforeLogin($request, $credentials);
            auth($current_panel->getId())->login($user, $credentials['remember_me']);
            $this->afterLogin($request);
            return redirect()->intended(route($current_panel->getPanelRouteName()))->with('success', 'You are logind');

        } else {
            return back()->withErrors([
                'identifiant' => 'The provided credentials do not match our records.',
                'password' => 'The provided credentials do not match our records.',
            ]);
        }
    }
    public function put(Request $request)
    {
        return back();
    }

    public function beforeLogin(Request $request, array $credentials)
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
            'title' => $this->title,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
        ];
    }

}
