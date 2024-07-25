<?php

namespace LVP\Widgets\DataWidgets;

use LVP\Enums\Alignment;
use Illuminate\Support\Facades\Storage;
use LVP\Facades\TableFilters\TableFilter;
use LVP\Widgets\DataWidgets\DataWidget;

class DataGrid extends DataWidget
{
    protected string $_type = 'grid';
}
