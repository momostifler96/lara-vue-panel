<?php

namespace LVP\Widgets\DataWidgets\DataTable\Columns;

use LVP\Enums\Alignment;
use LVP\Facades\TableColumn;

class LinkColumn extends TableColumn
{

    protected string $_type = 'link';
    protected $_l_column = null;
    protected $_target = '';
    protected $_url = '';
    protected int $_gap = 10;
    protected string $align_items = 'center';


    // public function __construct($column)
    // {
    //     $this->_l_column = $column;
    // }

    // public static function make($column)
    // {
    //     $instance = new static($column);
    //     $instance->_field = '';
    //     $instance->_label = '';
    //     $instance->_align = Alignment::LEFT->value;
    //     $instance->_editable = false;
    //     $instance->_sortable = false;
    //     $instance->_searchable = false;
    //     return $instance;
    // }


    public function target($target)
    {
        $this->_target = $target;
        return $this;
    }
    public function content($column)
    {
        $this->_l_column = $column;
        return $this;
    }
    public function url($url)
    {
        $this->_url = $url;
        return $this;
    }
    public function beforeRender(array $data)
    {
        $data['column'] = $this->_l_column->render();
        $data['target'] = $this->_target;
        $data['url'] = $this->_url;
        return $data;
    }

}
