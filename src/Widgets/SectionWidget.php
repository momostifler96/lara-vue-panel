<?php

namespace LVP\Widgets;

class SectionWidget extends LVPWidget
{
    protected string $_widget_type = 'section';
    protected string $_label;
    protected array $_widgets = [];
    protected int $_grid_cols = 1;


    public function __construct($label = '')
    {
        $this->_label = $label;
    }

    public static function make(string $label = '')
    {
        return new static($label);
    }


    public function gridCols(int $grid_cols)
    {
        $this->_grid_cols = $grid_cols;
        return $this;
    }
    public function widgets(array $widgets)
    {
        $this->_widgets = $widgets;
        return $this;
    }

    public function beforeRender(array $data): array
    {

        $data['grid_cols'] =   $this->_grid_cols;
        $data['widgets'] =    array_map(function ($w) {
            return $w->render();
        }, $this->_widgets);
        return  $data;
    }
}
