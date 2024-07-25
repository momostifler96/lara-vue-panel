<?php

namespace LVP\Facades\TableFilters;

class TableFilterText
{

    protected string $_field;
    protected string $_label;
    protected string $_placeholder = '';
    protected string $_default_value;


    public function __construct($field)
    {
        $this->_field = $field;
    }


    public static function make($field)
    {
        return new static($field);
    }

    public function placeholder(array $placeholder)
    {
        $this->_placeholder = $placeholder;
        return $this;
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


    public function render()
    {
        return [
            'field' => $this->_field,
            'placeholder' => $this->_placeholder,
            'label' => $this->_label,
        ];
    }
}
