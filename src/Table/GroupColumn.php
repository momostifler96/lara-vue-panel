<?php

namespace LVP\Table;

use LVP\Facades\TableColumn;

class GroupColumn extends TableColumn
{

    protected string $_type = 'group';
    protected array $_items = [];
    protected int $_gap = 10;
    protected string $align_items = 'center';

    public function items(array $items)
    {
        $this->_items[] = $items;
        return $this;
    }
    public function gap(int $gap)
    {
        $this->_gap = $gap;
        return $this;
    }
    public function alignItems(int $align)
    {
        $this->align_items = $align;
        return $this;
    }

    public function beforeRender(array $data)
    {
        $data['groups'] = array_map(fn($it) => array_map(fn($its) => ($its->render()), $it), $this->_items);
        $data['gap'] = $this->_gap;
        $data['align_items'] = $this->align_items;
        return $data;
    }

}
