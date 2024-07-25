<?php

namespace LVP\Form;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use LVP\Facades\FormField;

class SelectField extends FormField
{
    protected string $_component_path = 'select_field';


    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->_type = 'select';
    }


    public function options(array|callable $value)
    {

        if (is_callable($value)) {
            $value = $value();
        }
        $this->_options = $value;
        return $this;
    }
    public function multiple(bool $multiple = true)
    {
        $this->_multiple = $multiple;
        return $this;
    }
    public function filter()
    {
        $this->_filter = true;
        return $this;
    }
    public function ajaxQuery(callable $func)
    {
        $this->_ajax_call_backs[] = $func;

        return $this;
    }
}
