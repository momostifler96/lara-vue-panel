<?php

namespace LVP\Form;

use LVP\Facades\FormField;

class ToggleField extends FormField
{
    protected string $_component_path = 'toggle-field';
    protected string|bool $_true_value = true;
    protected string|bool $_false_value = false;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->_type = 'toggle-field';
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

    protected function beforeRender(array $data): array
    {
        $data['trueValue'] = $this->_true_value;
        $data['falseValue'] = $this->_false_value;
        return $data;
    }
}
