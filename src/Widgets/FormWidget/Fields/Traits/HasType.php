<?php

namespace LVP\Widgets\FormWidget\Fields\Traits;


trait HasType
{
    protected string $_type = '';

    public function type(string $type)
    {
        $this->_type = $type;
        return $this;
    }



}