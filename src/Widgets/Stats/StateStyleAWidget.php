<?php

namespace LVP\Widgets\Stats;

use LVP\Widgets\LVPWidget;

class StateStyleAWidget extends LVPWidget
{
    protected string $_widget_type = 'state_a';
    protected string $_value = '2000000';
    protected string $_percent = '34';
    protected string $_color = '#FFC500';
    protected string $_title = 'State A';
    protected string $_icon = 'state';
    protected string $_percentColor = '#00FF8A';

    public function __construct()
    {
    }

    public static function make(string $title, string|callable $value)
    {
        $instance = new static();
        $instance->_title = $title;

        if (is_callable($value)) {
            $instance->_value = $value();
        }
        $instance->_value = $value;
        return $instance;
    }

    public function value(string|callable $value)
    {
        if (is_callable($value)) {
            $this->_value = $value();
        }
        $this->_value = $value;
        return $this;
    }
    public function title(string $title)
    {
        $this->_title = $title;
        return $this;
    }
    public function icon(string $icon)
    {
        $this->_icon = $icon;
        return $this;
    }
    public function color(string $color)
    {
        $this->_color = $color;
        return $this;
    }
    public function percent(string $percent)
    {
        $this->_percent = $percent;
        return $this;
    }

    protected function beforeRender(array $data): array
    {

        $data['icon'] = $this->_icon;
        $data['title'] = $this->_title;
        $data['value'] = $this->_value;
        $data['color'] = $this->_color;
        $data['percent'] = $this->_percent;
        $data['percentColor'] = $this->_percentColor;
        return $data;
    }
}
