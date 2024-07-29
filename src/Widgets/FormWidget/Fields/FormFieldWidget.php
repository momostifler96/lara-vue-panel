<?php

namespace LVP\Widgets\FormWidget\Fields;

use LVP\Widgets\FormWidget\FormWidget;


class FormFieldWidget
{
    protected string $_name;
    protected string $_component;
    protected mixed $_default_value = null;
    protected string $_label = '';
    protected array $_rules = [];
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
    public function getValue(FormWidget $formWidget)
    {
        $formWidget->setFormData($this->_name, $this->_default_value);
    }
    public function required(bool $required = true)
    {
        $this->_rules = array_merge($this->_rules, ['required']);
        return $this;
    }

    protected function beforeRender(array $data): array
    {
        return $data;
    }
    public function render(FormWidget $formWidget)
    {
        $this->getValue($formWidget);
        $props = [
            'name' => $this->_name,
            'label' => $this->_label ?? $this->_label = str($this->_name)->kebab()->replace('-', ' ')->ucfirst(),
            'value' => $this->_default_value,
            'rules' => $this->_rules
        ];
        return [
            'name' => $this->_name,
            'component' => $this->_component,
            'props' => $this->beforeRender($props)
        ];
    }
}