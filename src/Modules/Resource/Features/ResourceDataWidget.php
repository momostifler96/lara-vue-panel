<?php
namespace LVP\Modules\Resource\Features;
use Illuminate\Http\Request;
use LVP\Widgets\DataWidgets\DataTableWidget;
class ResourceDataWidget
{
    public static function table()
    {
        return DataTableWidget::make();
    }
    public static function grid()
    {
        return DataTableWidget::make();
    }
}