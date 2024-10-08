<?php

namespace LVP\Widgets\FormWidget\Fields;

use LVP\Widgets\FormWidget\Fields\Traits\HasPlaceholder;
use LVP\Widgets\FormWidget\Fields\Traits\HasType;
use LVP\Widgets\FormWidget\FormWidget;

class SelectFieldWidget extends FormFieldWidget
{
    use HasPlaceholder;

    protected int $_max_selected = 100;
    protected int $_max_show_selected = 2;
    protected bool $_mutiple = false;
    protected bool $_filter = false;

    protected string $_component = 'select';
    protected array $_options = [];

    public function options(array|callable $options)
    {

        if (is_callable($options)) {
            $options = $options();
        }
        $this->_options = $options;
        return $this;
    }

    public function multiple()
    {
        $this->_mutiple = true;
        return $this;
    }
    public function filter()
    {
        $this->_filter = true;
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
        $data['filter'] = $this->_filter;
        $data['maxShowSelected'] = $this->_max_show_selected;
        return $data;
    }

}