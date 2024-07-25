<?php

namespace LVP\Widgets\Stats;

class ChartSerie
{
    public string $_name;
    public string $_color;
    public array $_data = [];
    public function __construct(string $name, string $color)
    {
        $this->_name = $name;
        $this->_color = $color;
    }

    public static function make(string $name, string $color)
    {
        return new static($name, $color);
    }

    public  function data(array|callable $data)
    {
        if (is_callable($data)) {
            $data = $data();
        }
        $this->_data = $data;
        return $this;
    }

    public  function addData($data)
    {
        if (is_callable($data)) {
            $data[] = $data();
        }
        $this->_data[] = $data;
        return $this;
    }

    public function render()
    {
        return [
            'name' => $this->_name,
            'color' => $this->_color,
            'data' => $this->_data,
            'tyle' => 'line',
        ];
    }
}
