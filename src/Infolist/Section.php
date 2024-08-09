<?php

namespace LVP\Infolist;

use LVP\Support\InfoContainer;


class Section extends InfoContainer
{
    protected string $_type = 'section';
    protected string $_label = '';
    protected string $_grid_cols = '1';
    protected int $_gap = 0;
    protected array $_infos = [];
    protected bool $_dismisable = false;

    public function gridCols(string $cols)
    {
        $this->_grid_cols = $cols;
        return $this;
    }
    public function gap(int $gap)
    {
        $this->_gap = $gap;
        return $this;
    }

    /**
     * @param \LVP\Support\Info[] $infos
     * @return static
     */
    public function infos(array $infos)
    {
        $this->_infos = $infos;
        return $this;
    }
    public function label(string $label)
    {
        $this->_label = $label;
        return $this;
    }

    public function dismisable(bool $dismisable = true)
    {
        $this->_dismisable = $dismisable;
        return $this;
    }

    protected function onRender(array $data)
    {
        $props = [];
        $props['label'] = $this->_label;
        $props['gridCols'] = $this->_grid_cols;
        $props['gap'] = $this->_gap;
        $props['dismisable'] = $this->_dismisable;
        $props['infos'] = array_map(fn($info) => $info->render($data), $this->_infos);

        return $props;
    }
}