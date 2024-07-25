<?php

namespace LVP\Facades\TableFilters;

class TableFilterCheckbox
{

    protected string $_field;
    protected string $_label;
    protected string $_value = '1';


    public function __construct($field)
    {
        $this->_field = $field;
    }


    public static function make($field)
    {
        return new static($field);
    }


    public function field()
    {
        return $this->_field;
    }
    public function label(string $label)
    {
        $this->_label = $label;
        return $this;
    }
    public function value(string $value)
    {
        $this->_value = $value;
        return $this;
    }

    public function render()
    {
        return [
            'field' => $this->_field,

        ];
    }
}
