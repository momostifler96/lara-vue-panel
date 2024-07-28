<?php

namespace LVP\Widgets\FormWidget\Fields\Traits;


class IsTextField
{
    protected string $_placeholder = '';
    protected string $_type = 'text';

    public function placeholder(string $placeholder)
    {
        $this->_placeholder = $placeholder;
        return $this;
    }

    public function type(string $type)
    {
        $this->_type = $type;
        return $this;
    }


}