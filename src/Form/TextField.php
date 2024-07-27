<?php

namespace LVP\Form;

use LVP\Facades\FormField;
use LVP\Traits\IsTextField;

class TextField extends FormField
{
    use IsTextField;

    protected string $_component_path = 'text-field';
    protected string $_icon;
    protected string $_iconPosition;
    protected bool $_autofocus = false;


    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->_type = 'text-field';
    }

    public function isEmail()
    {
        $this->_rules[] = 'email';
        return $this;
    }
    public function isPhoneNumber($prefix = '+', $max = 20)
    {

        $this->_rules[] = 'regex:/^' . preg_quote($prefix) . '[0-9]{' . $max . '}$/';
        return $this;
    }
    public function isEmailOrPhoneNumber($prefix = '+', $max = 100)
    {
        // $this->_rules[] = 'max:' . ($max + strlen($prefix));
        // $this->_rules[] = 'min:' . ($max + strlen($prefix) - 1);
        $this->_rules[] = 'regex:/^' . preg_quote($prefix) . '[0-9]{' . $max . '}$/|email';
        return $this;
    }
    public function isNumeric()
    {
        $this->_rules[] = 'numeric';
        return $this;
    }
    public function isProperName()
    {
        // $this->_rules[] = 'alpha';
        $this->_rules[] = 'regex:/^[\p{L}\s]+$/u';
        return $this;
    }
    public function updateFieldsOnSave(string $value)
    {
        $this->_label = $value;
        return $this;
    }
    public function updateFieldsOnChange(array $value)
    {
        $this->_watch = $value;
        return $this;
    }

    public function autofocus(bool $value = true)
    {

        $this->_autofocus = $value;
        return $this;
    }


    protected function beforeRender(array $data): array
    {
        $dt = [
            ...$data,
            'placeholder' => $this->_placeholder,
            'icon' => empty($this->_icon) ? null : $this->_icon,
            'iconPosition' => empty($this->_iconPosition) ? null : $this->_iconPosition,
            'autofocus' => $this->_autofocus,
            'mask' => $this->_mask,
        ];
        return $dt;
    }
}
