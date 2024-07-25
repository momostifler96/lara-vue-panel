<?php

namespace LVP\Widgets\DataWidgets;

use LVP\Enums\Alignment;
use Illuminate\Support\Facades\Storage;
use LVP\Facades\TableColumn;
use LVP\Facades\TableFilters\TableFilter;

class DataTable extends DataWidget
{

    protected string $_type = 'table';

    protected array $_filters;
    /**
     * Summary of _column
     * @var TableColumn[] $_columns
     */
    protected array $_columns;


    protected function fixeLastColumn()
    {
        $this->_fixe_last_column = true;
        return $this;
    }
    protected function fixeFirstColumn()
    {
        $this->_fixe_first_column = true;
        return $this;
    }
}
