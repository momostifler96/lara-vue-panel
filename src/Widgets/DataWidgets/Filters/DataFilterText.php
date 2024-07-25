<?php

namespace LVP\Widgets\DataWidgets\Filters;

use Illuminate\Database\Eloquent\Builder;

class DataFilterText extends DataFilterField
{
    protected string $_placeholder = '';

    public function placeholder($placeholder)
    {
        $this->_placeholder = $placeholder;
        return $this;
    }
    public function prepareRender(array $render_data)
    {
        $render_data['placeholder'] = $this->_placeholder;
        return $render_data;
    }

    public function apply(Builder $query, array $request_filter)
    {
        if (isset($request_filter[$this->_field]) && !empty($request_filter[$this->_field])) {
            $query->where($this->_field, 'LIKE', '%' . $request_filter[$this->_field] . '%');
        }
    }
}
