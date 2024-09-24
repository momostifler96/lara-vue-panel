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
    protected array $searchable = [];
    protected bool $_show_reset = false;
    protected bool $_auto_submit = false;
    protected string $_reset_button_label = 'Reset all';
    protected string $_submit_button_label = 'Submit';


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
    public function autoSubmit(bool $auto_submit)
    {
        $this->_auto_submit = $auto_submit;
        return $this;

    }
    public function resetButtonLabel(string $label)
    {
        $this->_reset_button_label = $label;
        return $this;

    }
    public function submitButtonLabel(string $label)
    {
        $this->_submit_button_label = $label;
        return $this;
    }
    public function searchable(array $fields)
    {
        $this->_searchable = $fields;
        return $this;
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
        return $this;

    }



    public function render()
    {

        return [
            'filters' => array_map(fn($item) => $item->render(), $this->_filters),
            'icon' => $this->_icon,
            'style' => $this->_style,
            'show_reset' => $this->_show_reset,
            'reset_button_label' => $this->_reset_button_label,
            'submit_button_label' => $this->_submit_button_label,
            'auto_submit' => $this->_auto_submit,
        ];
    }
}
