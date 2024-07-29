<?php

namespace LVP\Widgets\FormWidget\Fields\Traits;


trait HasPlaceholder
{
    protected string $_placeholder = '';

    public function placeholder(string $placeholder)
    {
        $this->_placeholder = $placeholder;
        return $this;
    }



}