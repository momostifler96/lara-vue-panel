<?php

namespace LVP\Form;

use LVP\Facades\FormField;

class ToggleField extends FormField
{
    protected string $_component_path = 'toogle_field';
    protected string $_true_value = 'true';
    protected string $_false_value = 'false';

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->_type = 'toggle';
    }

    public function trueValue(string $value)
    {
        $this->_true_value = $value;
        return $this;
    }
    public function falseValue(string $value)
    {
        $this->_false_value = $value;
        return $this;
    }
}
