<?php

namespace LVP\Defaults;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use LVP\Facades\Page;
use LVP\Facades\Panel;
use LVP\Widgets\LVPWidget;

class DashboardPage
{
    private Panel $panel;
    /**
     * Summary of panel
     * @var LVPWidget[]
     */
    private string $_title = 'Dashboard';
    private array $_widgets = [];
    private array $_header_actions = [];
    public function __construct(Panel $panel)
    {
        $this->panel = $panel;
    }
    public function setInfo(DashboardPage $dashboard)
    {
    }
    public function setup()
    {
        Route::get('/', function (Request $request) {
            return $this->index($request);
        })->name('index');
        Route::post('/', function (Request $request) {
            return $this->post($request);
        })->name('post');
    }

    public function widgets(array $widgets)
    {
        $this->_widgets = $widgets;
        // dd($widgets, $this);
        return $this;
    }
    public function headerActions(array $actions)
    {
        $this->_header_actions = $actions;
        return $this;
    }
    public function title(string $title)
    {
        $this->_title = $title;
        return $this;
    }
    public function index(Request $request)
    {
        $widgets = array_map(function ($w) {
            return $w->render();
        }, $this->_widgets);
        $header_actions = array_map(function ($w) {
            return $w->render();
        }, $this->_header_actions);
        $title =  $this->_title;
        $page_data = compact('widgets', 'header_actions', 'title');
        // dd($page_data, $this->_widgets);
        return Inertia::render('LVP/Dashboard', $page_data);
    }
    public function post(Request $request)
    {
        return back();
    }
}
