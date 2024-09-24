<?php

namespace LVP\Widgets\DataWidgets\DataTable\Columns;

use LVP\Facades\TableColumn;

class TextFieldColumn extends TableColumn
{

    public function searchable()
    {
        $this->_searchable = true;
        return $this;
    }
    public function __construct()
    {
        $this->_type = 'text-field';
        $this->_editable = false;
    }
}
