<?php

namespace LVP\Widgets\DataWidgets\Filters;

use Illuminate\Database\Eloquent\Builder;

class DataFilterField
{

    protected string $_field;
    protected string $_label;
    protected string $_component;

    public function __construct($field)
    {
        $this->_field = $field;
    }

    public static function make(string $field, string $label = '')
    {
        $static = new static($field);
        $static->_label = $label;
        return $static;
    }
    public function label(string $label)
    {
        $this->_label = $label;
        return $this;
    }

    public function prepareRender(array $render_data)
    {
        return $render_data;
    }
    public function apply(Builder $query, array $request_filter)
    {
        if (isset($request_filter[$this->_field]) && !empty($request_filter[$this->_field])) {
            $query->where($this->_field, $request_filter[$this->_field]);
        }
    }
    public function render()
    {
        $_render_data = [
            'field' => $this->_field,
            'component' => $this->_component,
            'label' => str($this->_label)->lower()->ucfirst(),
        ];
        $_render_data = $this->prepareRender($_render_data);
        return $_render_data;
    }
}
