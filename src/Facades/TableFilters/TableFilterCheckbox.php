<?php

namespace LVP\Facades\TableFilters;

class TableFilterCheckbox
{
    use IsFilter;

    protected string $_field;
    protected string $_label;
    protected array $_values = [];

    protected string|null $_is_relation = null;

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
    public function values(array $values)
    {
        $this->_values = $values;
        return $this;
    }
    public function isRelation($relation = null)
    {
        $this->_is_relation = $relation ?? $this->_field;
        return $this;
    }
    public function render()
    {
        return [
            'field' => $this->_field,
            'component' => 'checkbox',
            'props' => [
                'label' => $this->_label,
                'values' => $this->_values,
            ]
        ];
    }
}
