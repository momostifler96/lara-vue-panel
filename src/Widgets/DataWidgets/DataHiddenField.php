<?php

namespace LVP\Widgets\DataWidgets;

use LVP\Enums\Alignment;
use Illuminate\Support\Facades\Storage;
use LVP\Facades\TableColumn;
use LVP\Facades\TableFilters\TableFilter;

class DataHiddenField
{

    protected string $_field = 'table';
    protected string|null $_load_data_from = null;

    public function __construct(string $field, string|null $load_data_from = null)
    {
        $this->_field = $field;
        if ($load_data_from) {
            $this->_load_data_from = $load_data_from;
        }
    }

    public static function make(string $field, string|null $load_data_from = null)
    {
        return new static($field, $load_data_from);
    }

    public function field()
    {
        return $this->_field;
    }
    public function loadDataFrom()
    {
        return $this->_load_data_from;
    }

    public function render()
    {
        return [
            'field' => $this->_field,
            'load_data_from' => $this->_load_data_from ?? null,
            'type' => 'hidden'
        ];
    }
}
