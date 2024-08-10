<?php

namespace LVP\Widgets\DataWidgets\Filters;

use Illuminate\Database\Eloquent\Builder;

class DataFilterDropdown extends DataFilterField
{

    protected string $_placeholder = '';
    protected string $_component = 'select';

    protected string $_default_value;
    protected string $_option_value = 'value';
    protected string $_option_label = 'label';
    protected bool $_filter = false;
    protected string $_filter_key = 'value';
    protected bool $_multiple = false;
    protected array $_options = [];

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
    public function placeholder(string $placeholder)
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
    public function options(array $options)
    {
        $this->_options = $options;
        return $this;
    }



    public function apply(Builder $query, array $request_filter)
    {
        if (isset($request_filter[$this->_field]) && !empty($request_filter[$this->_field])) {
            $cols = explode('.', $this->_query);
            if (count($cols) == 1) {
                if ($this->_multiple) {
                    $query->whereIn($cols[0], explode(',', $request_filter[$this->_field]));
                } else {
                    $query->where($cols[0], $request_filter[$this->_field]);
                }
            } else if (count($cols) == 2 && $cols[1] != 'sum' && $cols[1] != 'count') {
                $query->whereHas($cols[0], function ($q) use ($cols, $request_filter) {
                    if ($this->_multiple) {
                        $q->whereIn($cols[1], explode(',', $request_filter[$this->_field]));
                    } else {
                        $q->where($cols[1], $request_filter[$this->_field]);
                    }
                });
            }

        }
    }

    public function prepareRender(array $render_data)
    {

        $render_data['options'] = $this->_options;
        $render_data['placeholder'] = $this->_placeholder;
        $render_data['multiple'] = $this->_multiple;
        $render_data['filter'] = $this->_filter;
        $render_data['optionValue'] = $this->_option_value;
        $render_data['optionLabel'] = $this->_option_label;
        $render_data['filter_key'] = $this->_filter_key;

        return $render_data;
    }
}
