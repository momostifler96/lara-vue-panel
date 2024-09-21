<?php
namespace LVP\Modules\Page\Features;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

trait Controllers
{
    public function index(Request $request, $sub_path = null)
    {
        $before_content_widgets = $this->renderWidgets($this->beforeContent($request));
        $after_content_widgets = $this->renderWidgets($this->afterContent($request));
        $page_titles = $this->getPageTitles();
        $route_names = $this->getRouteNames();
        $page_data = $this->onGetRequest($request, $sub_path);
        $actions = $this->getPageActions($request);
        $props = compact('before_content_widgets', 'after_content_widgets', 'page_data', 'page_titles', 'route_names', 'actions');
        return Inertia::render($this->view_path, $props);
    }
    public function post(Request $request)
    {
        try {
            $this->onPostRequest($request);
        } catch (\Throwable $th) {
            Session::flash('error', $th->getMessage());
        }
        return back();
    }


    public function put(Request $request)
    {
        $this->onPutRequest($request);
        return back();
    }

    public function delete(Request $request)
    {
        $this->onDeleteRequest($request);
        return back();
    }
}
