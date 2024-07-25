<?php

namespace LVP\Widgets\DataWidgets\Filters;

class DataFilterGroup
{

    protected string $_label;
    protected array $_fields = [];


    public function __construct(string $label)
    {
        $this->_label = $label;
    }

    public static function make(string $label)
    {
        return new static($label);
    }


    public function fields(array $fields)
    {
        $this->_fields = $fields;
    }
    public function getLabel()
    {
        return $this->_label;
    }

    public function render()
    {
        return [
            'fields' => $this->_fields,
            'label' => $this->_label,
        ];
    }
}
