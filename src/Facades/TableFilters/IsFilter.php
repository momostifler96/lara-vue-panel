<?php

namespace LVP\Facades\TableFilters;

use Illuminate\Database\Eloquent\Builder;


trait IsFilter
{
    protected $_on_apply;
    public function onApply($func)
    {
        $this->_on_apply = $func;
        return $this;
    }
    public function apply(Builder $query, array $request)
    {

        if (!empty($request[$this->_field])) {
            if (!empty($this->_on_apply)) {
                call_user_func_array([$this, '_on_apply',], $query, $request);
            } else if ($this->_is_relation) {
                $query->whereHas($this->_is_relation['relation'], function ($q) use ($request) {
                    $v = explode(',', $request[$this->_field]);
                    if (count($v) > 1) {
                        $q->whereIn($this->_is_relation['table'] . '.' . $this->_is_relation['column'], $v);
                    } else {
                        $q->where($this->_is_relation['table'] . '.' . $this->_is_relation['column'], $request[$this->_field]);
                    }
                });
            } else {
                $v = explode(',', $request[$this->_field]);
                if (count($v) > 1) {
                    $query->whereIn($this->_table . '.' . $this->_column, $v);
                } else {
                    $query->where($this->_field, $request[$this->_field]);
                }

            }
        }

    }
}