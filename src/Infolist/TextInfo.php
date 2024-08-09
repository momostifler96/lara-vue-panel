<?php

namespace LVP\Infolist;

use LVP\Support\Info;


class TextInfo extends Info
{
    protected string $_type = 'text';
    protected string $_align = 'vertical';
    protected string $_justify = 'center';

    public function justify(string $justify)
    {
        $this->_justify = $justify;
        return $this;
    }

    public function align(string $align)
    {
        $this->_align = $align;
        return $this;
    }

    protected function onRender(array $data)
    {
        $data['align'] = $this->_align;
        $data['justify'] = $this->_justify;
        return $data;
    }
}
