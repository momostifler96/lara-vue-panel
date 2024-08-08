<?php
namespace LVP\Modules\Page\Features;

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use LVP\Modules\Panel\Panel;

trait Actions
{
    public function registerRoutes()
    {

        Route::middleware($this->_middlewares)
            ->group(function () {
                if ($this->has_full_path) {
                    Route::get($this->slug . '/{slug?}', fn(Request $request) => $this->index($request))->where('slug', '.*')->middleware($this->index_middlewares)->name($this->route_name);
                } else {
                    Route::get($this->slug, fn(Request $request) => $this->index($request, null))->middleware($this->index_middlewares)->name($this->route_name);
                }
                // if (in_array('post', $this->http_methods)) {
                //     Route::post($this->slug, fn(Request $request) => $this->post($request))->middleware($this->post_middlewares)->name($this->route_name . '.post');
                // }
                // if (in_array('put', $this->http_methods)) {
                //     Route::put($this->slug, fn(Request $request) => $this->put($request))->middleware($this->put_middlewares)->name($this->route_name . '.put');
                // }
                // if (in_array('delete', $this->http_methods)) {
                //     Route::delete($this->slug, fn(Request $request) => $this->delete($request))->middleware($this->delete_middlewares)->name($this->route_name . '.delete');
                // }
            });
    }
    protected function setupPanel(Panel $panel_provider)
    {
        $this->_panel_route_name = $panel_provider->getPanelRouteName();
        $this->_panel_route_path = $panel_provider->getPanelRoutePath();
        $this->local = $panel_provider->getPanelLocal();
    }

    private function buildLabelsAndTitles()
    {
        $base_name = class_basename(get_called_class());
        $class_base_name = extractWordBefore($base_name, 'Page');

        $class_base_name_plural = str($class_base_name)->plural();

        if (empty($this->label)) {
            $this->label = $class_base_name_plural->kebab()->replace('-', ' ')->lower()->ucfirst();
        }
        if (empty($this->short_label)) {
            $this->short_label = $this->label;
        }

        $this->menu_label = $this->label;

        $this->page_title = ucfirst(!empty($this->page_title) ? $this->page_title : $this->label);
        $this->meta_title = ucfirst(!empty($this->meta_title) ? $this->meta_title : $this->label);

        $this->slug = $this->_panel_route_path . '/' . str(!empty($this->slug) ? $this->slug : $class_base_name)->kebab()->lower()->toString();
        $this->route_name = $this->_panel_route_name . '.' . str(!empty($this->route_name) ? $this->route_name : $class_base_name)->kebab()->lower()->toString();
    }
    private function renderWidgets(array $widgets)
    {
        // dd($widgets);
        $_widgets = [];
        foreach ($widgets as $key => $_widget) {
            $_widgets[] = $_widget->render();
        }
        return $_widgets;
    }
}
