<?php

namespace LVP\Widgets\FormWidget\Fields;


class FormFieldWidget
{
    protected string $_name;
    protected string $_component;
    protected mixed $_default_value;
    protected string $_label = '';
    public function __construct(string $name)
    {
        $this->_name = $name;
    }

    public static function make(string $name)
    {
        return new static($name);
    }
    public function label(string $label)
    {
        $this->_label = $label;
        return $this;
    }
    public function value(mixed $value)
    {
        $this->_default_value = $value;
        return $this;
    }

    protected function beforeRender(array $data): array
    {
        return $data;
    }
    public function render()
    {
        $props = [
            'name' => $this->_name,
            'label' => $this->_label,
        ];
        return [
            'name' => $this->_name,
            'component' => $this->_component,
            'props' => $this->beforeRender($props)
        ];
    }
}