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
    /**
     * Summary of panel
     * @var LVPWidget[]
     */
    public string $title = 'Dashboard';
    public string $meta_title = 'Dashboard';
    public string $meta_description = 'Dashboard';
    public array $widgets = [];
    public array $header_actions = [];
    public function __construct()
    {

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

        $widgets = $this->getWidgets();
        $header_actions = $this->getHeaderActions();
        $page_titles = $this->getPageTitles();
        $props = compact('widgets', 'header_actions', 'page_titles');
        return Inertia::render('LVP/Dashboard', $props);
    }
    public function post(Request $request)
    {
        return back();
    }
    public function put(Request $request)
    {
        return back();
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
    public function getHeaderActions()
    {
        return array_map(function ($a) {
            return $a->render();
        }, $this->header_actions);
    }
    public function getWidgets()
    {
        return array_map(function ($w) {
            return $w->render();
        }, $this->widgets);
    }
}
