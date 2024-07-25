<?php

namespace LVP\Facades;

class CustomPanelNavLink
{
    protected string $_label;
    protected string $_group;
    protected int $_position;
    protected string $_path;
    protected string $_icon;
    protected string $sub_path;
    public function __construct(string $label, string $path)
    {
        $this->_label = $label;
        $this->_path = $path;
        $this->_group = '';
        $this->_icon = 'stack';
        $this->_position = 0;
    }

    public static function make(string $label, string $link)
    {
        return new CustomPanelNavLink($label, $link);
    }
    public function group(string $group)
    {
        $this->_group = $group;
        return $this;
    }
    public function icon(string $group)
    {
        $this->_group = $group;
        return $this;
    }
    public function position(int $position)
    {
        $this->_position = $position;
        return $this;
    }
    public function setSubpath(string $path)
    {
        $this->sub_path = $path;
    }

    public function render()
    {
        return [
            'label' => $this->_label,
            'group' => $this->_group,
            'position' => $this->_position,
            'path' => url(empty($this->sub_path) ? $this->_path : $this->sub_path . '/' . $this->_path),
            'icon' => $this->_icon,
        ];
    }
}
