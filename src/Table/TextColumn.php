<?php

namespace LVP\Table;

use LVP\Facades\TableColumn;

class TextColumn extends TableColumn
{

    protected string $_color;
    protected bool $_has_badge = false;

    public function dateFormat(string $format)
    {
        $this->_date_format = $format;
        return $this;
    }


    public function color(string $color)
    {
        $this->_color = $color;
        return $this;
    }

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->_type = 'text';
        $this->_editable = false;
    }


    public function searchable()
    {
        $this->_searchable = true;
        return $this;
    }
}
