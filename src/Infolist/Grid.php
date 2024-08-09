<?php

namespace LVP\Infolist;



class Grid
{
    protected string $_type = 'grid';
    protected string $_grid_cols = '1';
    protected int $_gap = 3;
    protected array $_infos = [];


    public function __construct(array $infos)
    {
        $this->_infos = $infos;
    }

    public static function make(array $infos)
    {
        return new static($infos);
    }

    public function gridCols(string $cols)
    {
        $this->_grid_cols = $cols;
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
    public function gap(int $gap)
    {
        $this->_gap = $gap;
        return $this;
    }

    public function render(array &$data = [])
    {
        return [
            'type' => 'grid',
            'props' => [
                'infos' => array_map(fn($info) => $info->render($data), $this->_infos),
                'gridCols' => $this->_grid_cols,
                'gap' => $this->_gap,
            ],
        ];
    }
}