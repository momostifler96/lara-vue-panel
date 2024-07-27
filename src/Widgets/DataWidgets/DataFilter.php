<?php

namespace LVP\Widgets\DataWidgets;

use LVP\Widgets\DataWidgets\Filters\DataFilterCheckbox;
use LVP\Widgets\DataWidgets\Filters\DataFilterDropdown;
use LVP\Widgets\DataWidgets\Filters\DataFilterField;
use LVP\Widgets\DataWidgets\Filters\DataFilterGroup;
use LVP\Widgets\DataWidgets\Filters\DataFilterText;

class DataFilter
{

    protected array $_filters = [];
    protected array $_checkboxs = [];
    protected array $_dropdowns = [];
    protected array $_groups = [];
    protected array $_texts = [];
    protected string $_label = 'Filtres';
    protected string $_icon = 'lvp-filter-outline';
    protected string $_style = 'popover';
    protected string $_title = 'Filter';
    protected string $_value;
    protected bool $_show_reset = false;
    protected string $_reset_button_label = 'Reset all';


    public static function make()
    {
        return new static();
    }

    public function style(string $style = 'menu')
    {
        $this->_style = $style;
        return $this;
    }
    public function showReset(bool $show = true)
    {
        $this->_show_reset = $show;
        return $this;
    }
    public function resetButtonLabel(string $label)
    {
        $this->_reset_button_label = $label;
    }

    public function title(string $title)
    {
        $this->_title = $title;
        return $this;
    }
    /**
     * Summary of checkboxs
     * @param DataFilterCheckbox $checkboxs
     * @return static
     */
    public function checkboxs($checkboxs)
    {
        $this->_checkboxs[] = $checkboxs;
        return $this;
    }
    /**
     * Summary of checkboxs
     * @param DataFilterGroup[] $groups
     * @return static
     */
    public function groups(array $groups)
    {
        $this->_groups = $groups;
        return $this;
    }
    /**
     * Summary of dropdowns
     * @param DataFilterDropdown $filter
     * @return static
     */
    public function dropdowns($filter)
    {
        $this->_dropdowns[] = $filter;
        return $this;
    }
    /**
     * Summary of texts
     * @param DataFilterText $filter
     * @return static
     */
    public function texts($filter)
    {
        $this->_texts[] = $filter;
        return $this;
    }


    /**
     * @param DataFilterField[] $filters
     */

    public function filters(array $filters)
    {
        $this->_filters = $filters;
        // dd($filters);
        // foreach ($filters as $filter) {
        //     if ($filter instanceof DataFilterDropdown) {
        //         $this->dropdowns($filter);
        //     } else if ($filter instanceof DataFilterText) {
        //         $this->texts($filter);
        //     } else if ($filter instanceof DataFilterCheckbox) {
        //         $this->checkboxs($filter);
        //     }
        // }
    }



    public function render()
    {



        return [
            'filters' => array_map(fn($item) => $item->render(), $this->_filters),

            // [
            //     'checkboxs' => array_map(fn($item) => $item->render(), $this->_checkboxs),
            //     'groups' => array_map(fn($item) => $item->render(), $this->_groups),
            //     'dropdowns' => array_map(fn($item) => $item->render(), $this->_dropdowns),
            //     'texts' => array_map(fn($item) => $item->render(), $this->_texts),
            // ],
            'icon' => $this->_icon,
            'style' => $this->_style,
            'show_reset' => $this->_show_reset,
            'reset_button_label' => $this->_reset_button_label,
        ];
    }
}
