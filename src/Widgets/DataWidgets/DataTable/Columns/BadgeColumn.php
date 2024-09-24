<?php

namespace LVP\Widgets\DataWidgets\DataTable\Columns;

use LVP\Facades\TableColumn;

class BadgeColumn extends TableColumn
{

    protected string $_color;
    protected string $_field;
    protected array $_value_colors = [];

    public function dateFormat(string $format)
    {
        $this->_date_format = $format;
        return $this;
    }




    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->_type = 'badge';
        $this->_editable = false;
    }

    public function colors(array $colors)
    {
        $this->_value_colors = $colors;
        return $this;
    }
    public function searchable()
    {
        $this->_searchable = true;
        return $this;
    }
    public function beforeRender(array $data)
    {
        $data['value_colors'] = $this->_value_colors;

        return $data;
    }
}
