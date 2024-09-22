<?php

namespace LVP\Widgets\DataWidgets;

use LVP\Enums\Alignment;
class DataGridField
{
    protected string $_field = '';
    protected string $_load_data_from = '';

    public function __construct($field, $load_from = '')
    {
        $this->_field = $field;
        $this->_load_data_from = $load_from;
    }

    public static function make($field, $load_from = '')
    {
        return new static($field, $load_from);
    }

    public function render()
    {
        return [
            'field' => $this->_field,
            'load_data_from' => $this->_load_data_from,
        ];
    }
}