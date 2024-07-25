<?php

namespace LVP\Widgets\DataWidgets\Filters;

use Illuminate\Database\Eloquent\Builder;

class DataFilterCheckbox extends DataFilterField
{

    protected array $_options = [];

    public function options(array $options)
    {
        $this->_options = $options;
        return $this;
    }
    public function apply(Builder $query, array $request_filter)
    {
        if (isset($request_filter[$this->_field]) &&  !empty($request_filter[$this->_field])) {
            $query->whereIn($this->_field, explode(',', $request_filter[$this->_field]));
        }
    }
    public function prepareRender(array $render_data)
    {
        $render_data['options'] = $this->_options;
        return $render_data;
    }
}
