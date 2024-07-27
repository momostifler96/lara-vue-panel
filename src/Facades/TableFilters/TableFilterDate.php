<?php

namespace LVP\Facades\TableFilters;

use Illuminate\Database\Eloquent\Builder;

class TableFilterDate
{
    use IsFilter;


    protected string $_field;
    protected string $_label;
    protected bool $_range = false;
    protected string $_type = 'date';
    protected string $_min;
    protected string $_max;
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

    protected $_on_apply;
    public function onApply($func)
    {
        $this->_on_apply = $func;
        return $this;
    }
    public function apply(Builder $query, array $request)
    {

        if (!empty($request[$this->_field])) {
            if (!empty($_on_apply)) {
                call_user_func_array([$this, '_on_apply',], $query, $request);
            } else if ($this->_is_relation) {
                $query->whereHas($this->_field, function ($q) use ($request) {
                    $q->whereDate($this->_is_relation, $request[$this->_field]);
                    if ($this->_range) {
                        $q->whereBetween($this->_is_relation, $request[$this->_field]);
                    } else {
                        $q->whereDate($this->_is_relation, $request[$this->_field]);
                    }
                });
            } else {
                if ($this->_range) {
                    $query->whereBetween($this->_field, $request[$this->_field]);
                } else {
                    $query->whereDate($this->_field, $request[$this->_field]);
                }
            }
        }

    }
    public function render()
    {
        return [
            'field' => $this->_field,
            'component' => 'date',
            'props' => [
                'placeholder' => $this->_placeholder,
                'label' => $this->_label,
                'minDate' => @$this->_min,
                'maxDate' => @$this->_max,
                'defaultValue' => @$this->_default_value,
                'type' => $this->_type,
                'range' => $this->_range,
            ]
        ];
    }
}
