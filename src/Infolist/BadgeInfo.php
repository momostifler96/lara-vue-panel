<?php

namespace LVP\Infolist;

use LVP\Support\Info;


class BadgeInfo extends Info
{
    protected string $_type = 'text';
    protected string $_align = 'vertical';
    protected string $_justify = 'center';
    protected array $_colors = [];

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

    public function colors(array $colors)
    {
        $this->_colors = $colors;
        return $this;
    }

    protected function onRender(array $data)
    {
        $data['align'] = $this->_align;
        $data['justify'] = $this->_justify;
        $data['colors'] = $this->_colors;
        return $data;
    }
}
