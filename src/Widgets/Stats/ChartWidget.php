<?php

namespace LVP\Widgets\Stats;

use LVP\Enums\ChartWidgetType;
use LVP\Widgets\LVPWidget;

class ChartWidget extends LVPWidget
{
    public string $_type = 'bar';
    protected string $_widget_type = 'chart';
    protected $_categories = [];
    protected bool $_show_data_labels = true;
    protected string $_height = '100%';
    protected $_can_export = [
        'title' => 'chart stat',
        'png' => true,
        'csv' => true,
        'svg' => true,
    ];
    protected array $_series = [];
    public function __construct($label)
    {
        $this->_label = $label;
    }

    public static function make(string $label)
    {
        return new static($label);
    }

    public function addCategory($category)
    {
        $this->_categories[] = $category;
        return $this;
    }
    public function type(ChartWidgetType $type)
    {
        $this->_type = $type->value;
        return $this;
    }
    public function showDataLabels(bool $show = false)
    {
        $this->_show_data_labels = $show;
        return $this;
    }
    public function height(string $height)
    {
        $this->_height = $height;
        return $this;
    }
    public function categories(array|callable $category)
    {
        if (is_callable($category)) {
            $this->_categories = $category();
        } else {
            $this->_categories = $category;
        }
        return $this;
    }

    public function series(array|callable $series)
    {
        if (is_callable($series)) {
            $this->_series = $series();
        } else {
            $this->_series = $series;
        }

        return $this;
    }


    private function parseSeries()
    {
        $series = [];
        foreach ($this->_series as $key => $serie) {
            if ($serie instanceof ChartSerie) {
                $series[$key] = $serie->render();
            } else {
                $series[$key] = [
                    'name' => $serie['name'],
                    'data' => $serie['data'],
                    'color' => empty($serie['color']) ? '#FF8C00' : $serie['color'],
                ];
            }
        }

        return $series;
    }

    protected function beforeRender(array $data): array
    {
        $data['categories'] = $this->_categories;
        $data['series'] = $this->parseSeries();
        $data['type'] = $this->_type;
        $data['export'] = $this->_can_export;
        $data['show_data_labels'] = $this->_show_data_labels;
        $data['height'] = $this->_height;
        return $data;
    }
}
