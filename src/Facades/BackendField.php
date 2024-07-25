<?php

namespace LVP\Facades;

use LVP\Enums\LVPAction;

class BackendField
{

    protected string $_field;
    protected string $_value;
    protected bool $_required = false;
    protected array $_fill_on = [
        'create' => true,
        'edit' => true,
    ];


    public function __construct($field, string|bool|array|callable $value)
    {
        $this->_field = $field;
        $this->setValue($value);
        $this->_value = $value;
    }


    public static function make($field, string|bool|array|callable $value)
    {
        return new static($field, $value);
    }

    public function required(bool $value = true)
    {
        $this->_required = $value;
        return $this;
    }
    public function disabledOn(LVPAction $action, $value = true)
    {
        $this->_fill_on[$action->value] = !$value;
        return $this;
    }
    public function disabledOnCreate($value = true)
    {
        $this->_fill_on['create'] = !$value;
        return $this;
    }

    public function disabledOnEdit($value = true)
    {
        $this->_fill_on['edit'] = !$value;
        return $this;
    }

    public function canFillOn(LVPAction $action)
    {
        return $this->_fill_on[$action->value];
    }
    public function canFillOnCreate()
    {
        return $this->_fill_on['create'];
    }
    public function canFillOnEdit()
    {
        return $this->_fill_on['edit'];
    }


    public function setValue(string|bool|array|callable $value)
    {

        if (is_callable($value)) {
            $value = $value();
        }
        $this->_value = $value;
        return $this;
    }


    public function field()
    {
        return $this->_field;
    }

    public function getValue()
    {
        return $this->_value;
    }
    public function isRequired(): bool
    {
        return $this->_required;
    }

    public function render()
    {
        return [
            'field' => $this->_field,
            'value' => $this->_value,
            'required' => $this->_required
        ];
    }
}
