<?php
namespace LVP\Modules\Page\Features;

use Illuminate\Http\Request;

trait Hooks
{
    public function titleActions(Request $request)
    {
        return [];
    }
    public function headerActions(Request $request)
    {
        return [];
    }
    protected function beforeContent(Request $request)
    {
        return [];
    }
    protected function afterContent(Request $request)
    {
        return [];
    }
    protected function onGetRequest(Request $request, $sub_path)
    {
        return [];
    }
    protected function onPostRequest(Request $request)
    {
        return [];
    }
    protected function onPutRequest(Request $request)
    {
        return [];
    }
    protected function onDeleteRequest(Request $request)
    {
        return [];
    }
}
