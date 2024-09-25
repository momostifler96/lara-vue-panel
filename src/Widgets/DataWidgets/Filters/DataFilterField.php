<?php

namespace LVP\Widgets\DataWidgets\Filters;

use Illuminate\Database\Eloquent\Builder;

class DataFilterField
{

    protected string $_field;
    protected string $_query_from;
    protected string $_label;
    protected string $_component;
    protected string $_col_span = "1";

    protected string $_query;
    protected $_call_on_query;


    public function __construct($field)
    {
        $this->_field = $field;
        $this->_query = $field;
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

    public function colSpan(string $col_span)
    {
        $this->_col_span = $col_span;
        return $this;
    }

    public function prepareRender(array $render_data)
    {
        return $render_data;
    }
    public function onQuery(callable $call)
    {
        $this->_call_on_query = [$call];
        return $this;
    }
    public function apply(Builder $query, array $request_filter)
    {

        if (isset($request_filter[$this->_field]) && !empty($request_filter[$this->_field])) {
            $query->where($this->_query, $request_filter[$this->_field]);
            $cols = explode('.', $this->_query);
            if (count($cols) == 1) {
                $query->where($cols[0], $request_filter[$this->_field]);
            } else if ($cols[1] == 'count') {
                $query->whereHas($cols[0], function (Builder $q) use ($cols, $request_filter) {
                });
            } else if ($cols[1] == 'sum') {
                $query->where($cols[0], function (Builder $q) use ($cols, $request_filter) {
                });
            } else if (count($cols) == 2 && $cols[1] != 'count') {
                $query->whereHas($cols[0], function ($q) use ($cols, $request_filter) {
                    $q->where($cols[1], $request_filter[$this->_field]);
                });
            }
            $this->execOnQuery($query, $request_filter[$this->_field], $request_filter);

        }

    }

    protected function execOnQuery(Builder $query, $val, array $request_filter)
    {
        if (isset($this->_call_on_query)) {
            $this->_call_on_query[0]($query, $val, $request_filter, $this->_field);
        }
    }
    public function queryFrom(string $query)
    {
        $this->_query = $query;
        return $this;
    }
    public function render()
    {
        $_render_data = [
            'field' => $this->_field,
            'col_span' => $this->_col_span,
            'component' => $this->_component,
            'label' => str($this->_label)->lower()->ucfirst(),
        ];
        $_render_data = $this->prepareRender($_render_data);
        return $_render_data;
    }
}
