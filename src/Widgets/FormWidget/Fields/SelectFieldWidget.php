<?php

namespace LVP\Widgets\FormWidget\Fields;

use LVP\Widgets\FormWidget\Fields\Traits\HasPlaceholder;
use LVP\Widgets\FormWidget\Fields\Traits\HasType;

class SelectFieldWidget extends FormFieldWidget
{
    use HasPlaceholder;

    protected string $_max_selected = 100;
    protected string $_max_show_selected = 2;
    protected string $_mutiple = false;

    protected string $_component = 'selcted-field';
    protected array $_options = [];

    public function options(array|callable $options)
    {

        if (is_callable($options)) {
            $options = $options();
        }
        $this->_options = $options;
        return $this;
    }

    public function multiple(bool $multiple = true)
    {
        $this->_mutiple = $multiple;
        return $this;
    }

    public function maxSelected(int $max = 100)
    {
        $this->_max_selected = $max;
        return $this;
    }

    public function maxShowSelected(int $max = 2)
    {
        $this->_max_show_selected = $max;
        return $this;
    }


    protected function beforeRender(array $data): array
    {
        $data['options'] = $this->_options;
        $data['multiple'] = $this->_mutiple;
        $data['maxSelected'] = $this->_max_selected;
        $data['maxShowSelected'] = $this->_max_show_selected;
        return $data;
    }

}