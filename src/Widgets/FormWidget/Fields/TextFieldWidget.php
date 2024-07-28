<?php

namespace LVP\Widgets\FormWidget\Fields;


class TextFieldWidget
{
    protected string $_name;
    protected string $_type;
    protected string $_label;
    public function __construct(string $name, string $label)
    {
        $this->_name = $name;
        $this->_label = $label;
    }

    public static function make(string $name, string $label)
    {
        return new static($name, $label);
    }

    public function render()
    {

    }
}