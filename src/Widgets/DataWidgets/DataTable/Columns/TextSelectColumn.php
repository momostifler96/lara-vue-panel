<?php

namespace LVP\Widgets\DataWidgets\DataTable\Columns;

use LVP\Facades\TableColumn;

class TextSelectColumn extends TableColumn
{

    public function searchable()
    {
        $this->_searchable = true;
        return $this;
    }
    public function __construct()
    {
        $this->_type = 'select';
        $this->_editable = false;
    }
}
