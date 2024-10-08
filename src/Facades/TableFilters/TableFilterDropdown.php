<?php

namespace LVP\Facades\TableFilters;

use Illuminate\Database\Eloquent\Builder;

class TableFilterDropdown
{

    use IsFilter;
    protected string $_field;
    protected string $_placeholder = '';
    protected string $_label;
    protected string $_default_value;
    protected string $_option_value = 'value';
    protected string $_option_label = 'label';
    protected bool $_filter = false;
    protected array|null $_is_relation = null;
    protected string $_filter_key = 'value';
    protected bool $_multiple = false;
    protected array $_options = [];


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
        // dd($this);
        return $this;
    }
    public function optionValue(string $value)
    {
        $this->_option_value = $value;
        return $this;
    }
    public function optionLabel(string $value)
    {
        $this->_option_label = $value;
        return $this;
    }
    public function placeholder(array $placeholder)
    {
        $this->_placeholder = $placeholder;
        return $this;
    }
    public function filter(bool $filter = true)
    {
        $this->_filter = $filter;
        return $this;
    }
    public function multiple(bool $multiple = true)
    {
        $this->_multiple = $multiple;
        return $this;
    }
    public function onRelation($relation = null, $column = 'id', $table = null)
    {
        $this->_is_relation = $relation ? [
            'relation' => $relation,
            'column' => $column,
            'table' => $table ?? str($relation)->snake(),
        ] : [
            'column' => $column,
            'relation' => $this->_field,
            'table' => $table ?? str($relation)->snake(),
        ];

        return $this;
    }
    public function options(array $options)
    {
        $this->_options = $options;
        return $this;
    }


    public function render()
    {
        return [
            'field' => $this->_field,
            'component' => 'select',
            'props' => [
                'options' => $this->_options,
                'placeholder' => $this->_placeholder,
                'label' => $this->_label,
                'multiple' => $this->_multiple,
                'filter' => $this->_filter,
                'optionValue' => $this->_option_value,
                'optionLabel' => $this->_option_label,
                'filter_key' => $this->_filter_key,
            ],

        ];
    }
}
