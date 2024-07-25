<?php

namespace LVP\Widgets\DataWidgets\Actions;

class DataActionButton
{

    protected string $_label = '';
    protected string $_action = 'edit';
    protected string $_color = '';
    protected string $_icon = '';


    public function __construct(string $action)
    {
        $this->_action = $action;
        if ($action == 'delete') {
            $this->_icon = 'delete';
            $this->_label = 'delete';
        } else if ($action == 'view') {
            $this->_icon = 'view';
            $this->_label = 'view';
        } else if ($action == 'edit') {
            $this->_icon = 'edit';
            $this->_label = 'edit';
        } else if ($action == 'export-exel') {
            $this->_icon = 'export';
            $this->_label = 'export';
        } else if ($action == 'export-csv') {
            $this->_icon = 'export';
            $this->_label = 'export';
        }
    }


    public static function make(string $action)
    {
        return new static($action);
    }

    public function label(string $label)
    {
        $this->_label = $label;
        return $this;
    }
    public function icon(string $icon)
    {
        $this->_icon = $icon;
        return $this;
    }
    public function action(string $action)
    {
        $this->_action = $action;
        return $this;
    }
    public function color(string $color)
    {
        $this->_color = $color;
        return $this;
    }


    public function render()
    {
        return  [
            'label' => $this->_label,
            'action' => $this->_action,
            'icon' => $this->_icon,
            'color' => $this->_color,
        ];
    }
}
