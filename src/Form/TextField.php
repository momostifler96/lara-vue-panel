<?php

namespace LVP\Form;

use LVP\Facades\FormField;
use LVP\Traits\IsTextField;

class TextField extends FormField
{
    use IsTextField;

    protected string $_component_path = 'text_field';


    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->_type = 'text';
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
}
